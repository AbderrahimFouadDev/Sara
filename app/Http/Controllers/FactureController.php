<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        // This can remain if you need a full-page load
        $factures = Facture::with('fournisseur')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('customer.components.factures.liste_factures', compact('factures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fournisseur_name' => 'required|string',
            'date_facture' => 'required|date',
            'groupe_facture' => 'required|string',
        ]);

        // Save fournisseur or find existing one
        $fournisseur = Fournisseur::firstOrCreate(
            ['fournisseur_name' => $data['fournisseur_name']],
            ['fournisseur_actif' => true]
        );

        // Generate numero for the facture
        $numero = Facture::generateNumero();

        $facture = Facture::create([
            'id_fournisseur' => $fournisseur->id,
            'fournisseur_name' => $data['fournisseur_name'],
            'date_facture' => $data['date_facture'],
            'groupe_facture' => $data['groupe_facture'],
            'numero' => $numero,
            'statut' => 'pending'
        ]);

        return response()->json(['success' => true, 'facture' => $facture]);
    }

    public function show(Facture $facture)
    {
        return view('customer.components.factures.voir_facture', compact('facture'));
    }

    public function update(Request $request, Facture $facture)
    {
        try {
            $validated = $request->validate([
                'date_facture' => 'required|date',
                'groupe_facture' => 'required|string',
                'notes' => 'nullable|string',
                'statut' => 'required|string|in:pending,paid,overdue',
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price_ht' => 'required|numeric|min:0',
                'products.*.total_ht' => 'required|numeric|min:0',
                'products.*.tva_rate' => 'required|numeric',
                'products.*.tva_amount' => 'required|numeric|min:0',
                'products.*.total_ttc' => 'required|numeric|min:0'
            ]);

            // Update basic facture information
            $facture->update([
                'date_facture' => $validated['date_facture'],
                'groupe_facture' => $validated['groupe_facture'],
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

            $facture->products()->sync($productsData);

            // Calculate and update totals
            $montant_ht = collect($validated['products'])->sum(function ($product) {
                return $product['price_ht'] * $product['quantity'];
            });
            
            $montant_tva = collect($validated['products'])->sum('tva_amount');
            $montant_ttc = collect($validated['products'])->sum('total_ttc');

            $facture->update([
                'montant_ht' => $montant_ht,
                'montant_tva' => $montant_tva,
                'montant_ttc' => $montant_ttc
            ]);

            // Reload the facture with its relationships
            $facture->load(['fournisseur', 'products']);

            return response()->json([
                'success' => true,
                'message' => 'Facture mise à jour avec succès',
                'facture' => $facture
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Facture $facture)
    {
        try {
            $facture->delete();
            return response()->json([
                'success' => true,
                'message' => 'Facture supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Load a Blade component via AJAX (used for liste_factures and creer_facture)
     */
    public function loadComponent($view, Request $request)
    {
        switch($view) {
            case 'factures/liste_factures':
                // Apply optional filter from query string
                $filter = $request->get('filter','all');
                $query = Facture::with('fournisseur')->orderBy('created_at','desc');
                if ($filter !== 'all') {
                    $query->where('statut', $filter);
                }
                $factures = $query->get();
                return view('customer.components.factures.liste_factures', compact('factures'));

            case 'factures/creer_facture':
                return view('customer.components.factures.creer_facture');

            case 'factures/voir_facture':
                $facture = Facture::with(['fournisseur', 'products'])->findOrFail($request->id);
                return view('customer.components.factures.voir_facture', compact('facture'));

            case 'factures/edit_facture':
                $facture = Facture::with(['fournisseur', 'products'])->findOrFail($request->id);
                $products = Product::orderBy('nom')->get();
                return view('customer.components.factures.edit_facture', compact('facture', 'products'));

            case 'factures/print_facture':
                $facture = Facture::with(['fournisseur', 'products'])->findOrFail($request->id);
                return view('customer.components.factures.print_facture', compact('facture'));

            default:
                // Extract the view name without the prefix
                $viewPath = str_replace('factures/', '', $view);
                return view("customer.components.factures.{$viewPath}");
        }
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $factures = Facture::with('fournisseur')->get();
        
        $headers = [
            'ID',
            'Numéro',
            'Date de création',
            'Date de modification',
            'Date de facture',
            'Fournisseur ID',
            'Nom du fournisseur',
            'Groupe facture',
            'Montant',
            'Statut',
            'Notes'
        ];
        
        $callback = function() use ($factures, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($factures as $f) {
                fputcsv($file, [
                    $f->id,
                    $f->numero,
                    $f->created_at->format('d/m/Y H:i:s'),
                    $f->updated_at->format('d/m/Y H:i:s'),
                    $f->date_facture->format('d/m/Y'),
                    $f->id_fournisseur,
                    $f->fournisseur_name,
                    $f->groupe_facture,
                    number_format($f->montant, 2),
                    $f->statut,
                    $f->notes
                ]);
            }
            
            fclose($file);
        };
        
        $filename = 'factures_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $headers);
    }

    public function print(Facture $facture)
    {
        $facture->load(['fournisseur', 'products']);
        return view('customer.components.factures.print_facture', compact('facture'));
    }
}
