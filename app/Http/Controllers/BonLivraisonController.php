<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonLivraisonController extends Controller
{
    public function index(Request $request)
    {
        $bons = BonLivraison::with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.components.bon_livraison.liste_bon_livraison', compact('bons'));
    }

    public function store(Request $request)
    {
        try {
            // 1) Validate only the fields your form actually sends
            $validated = $request->validate([
                'client_name'        => 'required|string|max:255',
                'client_id'          => 'nullable|exists:clients,id',
                'date_livraison'     => 'required|date',
                'adresse_livraison'  => 'nullable|string',
                'mode_transport'     => 'nullable|string',
                'reference_commande' => 'nullable|string',
            ]);

            // 2) Resolve or create the client so client_id is never null
            if (!empty($validated['client_id'])) {
                // fetch existing
                $client = Client::find($validated['client_id']);
            } else {
                // create or fetch by name
                $client = Client::firstOrCreate(
                    ['client_name' => $validated['client_name']],
                    ['client_actif' => true]
                );
            }

            // 3) Overwrite payload with reliable client data
            $validated['client_id']   = $client->id;
            $validated['client_name'] = $client->client_name;

            // 4) Auto-generate your BL number and default status
            $validated['numero_bl'] = BonLivraison::generateNumero();
            $validated['etat']      = 'pending';

            // 5) Persist
            $bonLivraison = BonLivraison::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bon de livraison créé avec succès',
                'data'    => $bonLivraison,
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
            // Return the real message for debugging; remove in production
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    public function show(BonLivraison $bonLivraison)
    {
        $bon_livraison = $bonLivraison->load(['client', 'products']);
        return view('customer.components.bon_livraison.voir_bon_livraison', compact('bon_livraison'));
    }

    public function update(Request $request, BonLivraison $bonLivraison)
    {
        try {
            DB::beginTransaction();

        $validated = $request->validate([
                'date_livraison' => 'required|date',
                'etat' => 'required|string',
                'notes' => 'nullable|string',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price_ht' => 'required|numeric|min:0',
                'products.*.remise_percent' => 'required|numeric|min:0|max:100',
                'products.*.remise_amount' => 'required|numeric|min:0',
                'products.*.total_ht' => 'required|numeric|min:0',
                'products.*.tva_amount' => 'required|numeric|min:0',
                'products.*.total_ttc' => 'required|numeric|min:0'
            ]);

            // Update bon livraison main data
            $bonLivraison->update([
                'date_livraison' => $validated['date_livraison'],
                'etat' => $validated['etat'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Remove existing products
            $bonLivraison->products()->detach();

            // Add new products
            foreach ($validated['products'] as $product) {
                $bonLivraison->products()->attach($product['id'], [
                    'quantity' => $product['quantity'],
                    'price_ht' => $product['price_ht'],
                    'remise_percent' => $product['remise_percent'],
                    'remise_amount' => $product['remise_amount'],
                    'total_ht' => $product['total_ht'],
                    'tva_rate' => 20.00, // Fixed TVA rate
                    'tva_amount' => $product['tva_amount'],
                    'total_ttc' => $product['total_ttc']
                ]);
            }

            // Calculate totals
            $total_ht = collect($validated['products'])->sum('total_ht');
            $total_tva = collect($validated['products'])->sum('tva_amount');
            $total_ttc = collect($validated['products'])->sum('total_ttc');
            $total_remise = collect($validated['products'])->sum('remise_amount');

            $bonLivraison->update([
                'montant_ht' => $total_ht,
                'montant_tva' => $total_tva,
                'montant_ttc' => $total_ttc,
                'montant_remise' => $total_remise
            ]);

            DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Bon de livraison mis à jour avec succès',
                'bon_livraison' => $bonLivraison->load('products')
        ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(BonLivraison $bonLivraison)
    {
        try {
            $bonLivraison->delete();
            
            // Check if request wants JSON response (API call)
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bon de livraison supprimé avec succès',
                ]);
            }
            
            // Otherwise return redirect for form submission
            return redirect()->back()->with('success', 'Bon de livraison supprimé avec succès');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'foreign key constraint fails') || $e->getCode() === '23000') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce bon de livraison ne peut pas être supprimé car il est lié à d\'autres éléments.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Ce bon de livraison ne peut pas être supprimé car il est lié à d\'autres éléments.');
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors de la suppression du bon de livraison: ' . $e->getMessage());
        }
    }

    public function print(BonLivraison $bonLivraison)
    {
        $bon_livraison = $bonLivraison->load(['client', 'products']);
        return view('customer.components.bon_livraison.print_bon_livraison', compact('bon_livraison'));
    }

    public function loadComponent($view, Request $request)
    {
        switch ($view) {
            case 'bon_livraison/liste_bon_livraison':
                $filter = $request->get('filter', 'all');
                $query = BonLivraison::with('client')->orderBy('created_at', 'desc');
                if ($filter !== 'all') {
                    $query->where('etat', $filter);
                }
                $bons = $query->get();
                return view('customer.components.bon_livraison.liste_bon_livraison', compact('bons'));

            case 'bon_livraison/creer_bon_livraison':
                return view('customer.components.bon_livraison.creer_bon_livraison');

            case 'bon_livraison/voir_bon_livraison':
                $bon_livraison = BonLivraison::with(['client', 'products'])->findOrFail($request->id);
                return view('customer.components.bon_livraison.voir_bon_livraison', compact('bon_livraison'));

            case 'bon_livraison/edit_bon_livraison':
                $bon_livraison = BonLivraison::with(['client', 'products'])->findOrFail($request->id);
                $products = \App\Models\Product::all();
                return view('customer.components.bon_livraison.edit_bon_livraison', compact('bon_livraison', 'products'));

            default:
                // If the view contains a directory separator, use it as is
                if (strpos($view, '/') !== false) {
                    return view("customer.components.{$view}");
                }
                // Otherwise, assume it's in the bon_livraison directory
                return view("customer.components.bon_livraison.{$view}");
        }
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $bons = BonLivraison::with('client')->get();
        
        $headers = [
            'ID',
            'Numéro BL',
            'Date de création',
            'Date de modification',
            'Date de livraison',
            'Client ID',
            'Nom du client',
            'Adresse de livraison',
            'Montant',
            'Statut',
            'Notes'
        ];
        
        $callback = function() use ($bons, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($bons as $bl) {
                fputcsv($file, [
                    $bl->id,
                    $bl->numero_bl,
                    $bl->created_at->format('d/m/Y H:i:s'),
                    $bl->updated_at->format('d/m/Y H:i:s'),
                    $bl->date_livraison->format('d/m/Y'),
                    $bl->client_id,
                    $bl->client_name,
                    $bl->adresse_livraison,
                    number_format($bl->montant, 2),
                    $bl->etat,
                    $bl->remarques
                ]);
            }
            
            fclose($file);
        };
        
        $filename = 'bons_livraison_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $headers);
    }
    

}
