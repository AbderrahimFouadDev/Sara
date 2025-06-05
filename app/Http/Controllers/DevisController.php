<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Product;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $devis = Devis::with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('customer.components.devis.liste_devis', compact('devis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string',
            'date_devis' => 'required|date',
            'groupe_devis' => 'required|string',
        ]);

        // Save client or find existing one
        $client = Client::firstOrCreate(['client_name' => $data['client_name']]);

        // Generate numero for the devis
        $numero = Devis::generateNumero();  // Generate the number

        $devis = Devis::create([
            'client_id'    => $client->id,
            'client_name'  => $data['client_name'], 
            'date_devis'   => $data['date_devis'],
            'groupe_devis' => $data['groupe_devis'],
            'numero'       => $numero,  // Add numero field
        ]);

        return response()->json(['success' => true, 'devis' => $devis]);
    }

    public function show(Devis $devis)
    {
        $devis->load(['client', 'products']);
        return view('customer.components.devis.voir_devis', compact('devis'));
    }
    public function edit(Devis $devis)
    {
        return view('customer.components.edit_devis', compact('devis'));
    }
    public function update(Request $request, Devis $devis)
    {
        try {
            $validated = $request->validate([
                'date_devis'   => 'required|date',
                'validite'     => 'required|integer|min:1',
                'notes'        => 'nullable|string',
                'statut'       => 'required|in:en attente,accepte,rejete',
                'products'     => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price_ht' => 'required|numeric|min:0',
                'products.*.total_ht' => 'required|numeric|min:0',
                'products.*.tva_rate' => 'required|numeric',
                'products.*.tva_amount' => 'required|numeric',
                'products.*.total_ttc' => 'required|numeric'
            ]);

            // Update basic devis information
            $devis->update([
                'date_devis' => $validated['date_devis'],
                'notes' => $validated['notes'],
                'statut' => $validated['statut']
            ]);

            // Sync products with their pivot data
            $productsData = collect($validated['products'])->mapWithKeys(function ($item) {
                return [$item['id'] => [
                    'quantity' => $item['quantity'],
                    'price_ht' => $item['price_ht'],
                    'tva_rate' => $item['tva_rate'],
                    'tva_amount' => $item['tva_amount'],
                    'total_ttc' => $item['total_ttc']
                ]];
            })->all();

            $devis->products()->sync($productsData);

            // Calculate and update totals
            $montant_ht = collect($validated['products'])->sum(function ($product) {
                return $product['price_ht'] * $product['quantity'];
            });
            
            $montant_tva = collect($validated['products'])->sum('tva_amount');
            $montant_ttc = collect($validated['products'])->sum('total_ttc');

            $devis->update([
                'montant_ht' => $montant_ht,
                'montant_tva' => $montant_tva,
                'montant_ttc' => $montant_ttc
            ]);

            // Reload the devis with its relationships
            $devis->load(['client', 'products']);

            return response()->json([
                'success' => true,
                'message' => 'Devis mis à jour avec succès',
                'devis' => $devis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Devis $devis)
    {
        try {
            $devis->delete();
            return response()->json([
                'success' => true,
                'message' => 'Devis supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function loadComponent($view, Request $request)
    {
        switch($view) {
            case 'devis/liste_devis':
                $filter = $request->get('filter', 'all');
                $query = Devis::with(['client', 'products'])->orderBy('created_at', 'desc');
                
                if ($filter !== 'all') {
                    // Check for expired status
                    if ($filter === 'expire') {
                        $query->where('date_devis', '<', now()->subDays(15));
                    } else {
                        $query->where('statut', $filter);
                    }
                }
                
                $devis = $query->get();

                // Update expired status for devis older than 15 days
                foreach ($devis as $item) {
                    if ($item->date_devis->addDays(15)->isPast()) {
                        $item->update(['statut' => 'expire']);
                    }
                }

                // Reload after potential status updates
                $devis = $query->get();
                
                return view('customer.components.devis.liste_devis', compact('devis'));

            case 'devis/creer_devis':
                return view('customer.components.devis.creer_devis');

            case 'devis/voir_devis':
                $devis = Devis::with(['client', 'products'])->findOrFail($request->id);
                return view('customer.components.devis.voir_devis', compact('devis'));

            case 'devis/edit_devis':
                $devis = Devis::with(['client', 'products'])->findOrFail($request->id);
                $products = Product::orderBy('nom')->get();
                return view('customer.components.devis.edit_devis', compact('devis', 'products'));

            case 'devis/print_devis':
                $devis = Devis::with(['client', 'products'])->findOrFail($request->id);
                return view('customer.components.devis.print_devis', compact('devis'));

            default:
                $viewPath = str_replace('devis/', '', $view);
                return view("customer.components.devis.{$viewPath}");
        }
    }

    public function convertToFacture(Devis $devis)
    {
        try {
            // Create a new facture from the devis
            $facture = Facture::create([
                'numero'         => Facture::generateNumero(),
                'client_id'      => $devis->client_id,
                'client_name'    => $devis->client_name,
                'date_facture'   => now(),
                'groupe_facture' => $devis->groupe_devis,
                'montant'        => $devis->montant,
                'notes'          => $devis->notes,
                'statut'         => 'pending',
                'devis_id'       => $devis->id
            ]);

            // Update devis status
            $devis->update(['statut' => 'accepted']);

            return response()->json([
                'success' => true,
                'message' => 'Devis converti en facture avec succès',
                'facture' => $facture
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la conversion: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $devis = Devis::with('client')->get();
        
        $headers = [
            'ID',
            'Numéro',
            'Date de création',
            'Date de modification',
            'Date du devis',
            'Client ID',
            'Nom du client',
            'Groupe devis',
            'Montant',
            'Statut',
            'Email client',
            'Téléphone client',
            'Adresse client'
        ];
        
        $callback = function() use ($devis, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($devis as $d) {
                fputcsv($file, [
                    $d->id,
                    $d->numero,
                    $d->created_at->format('d/m/Y H:i:s'),
                    $d->updated_at->format('d/m/Y H:i:s'),
                    $d->date_devis->format('d/m/Y'),
                    $d->client_id,
                    $d->client_name,
                    $d->groupe_devis,
                    number_format($d->montant, 2),
                    $d->statut,
                    $d->client ? $d->client->email : '',
                    $d->client ? $d->client->telephone : '',
                    $d->client ? $d->client->adresse : ''
                ]);
            }
            
            fclose($file);
        };
        
        $filename = 'devis_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $headers);
    }

    public function print(Devis $devis)
    {
        $devis->load(['client', 'products']);
        return view('customer.components.devis.print_devis', compact('devis'));
    }
}

