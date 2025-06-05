<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Product;
use App\Models\DevisProduct;
use Illuminate\Http\Request;

class DevisProductController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'devis_id' => 'required|exists:devis,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'tva_rate' => 'required|numeric',
                'tva_amount' => 'required|numeric',
                'total_ttc' => 'required|numeric'
            ]);

            // Check if product already exists in this devis
            $existingProduct = DevisProduct::where('devis_id', $validated['devis_id'])
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit existe déjà dans le devis'
                ], 400);
            }

            // Create new devis product
            $devisProduct = DevisProduct::create($validated);

            // Update devis totals
            $devis = Devis::find($validated['devis_id']);
            $devis->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté avec succès',
                'devis_product' => $devisProduct
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du produit: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $devisProduct = DevisProduct::findOrFail($id);

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'tva_rate' => 'required|numeric',
                'tva_amount' => 'required|numeric',
                'total_ttc' => 'required|numeric'
            ]);

            $devisProduct->update($validated);

            // Update devis totals
            $devisProduct->devis->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Produit mis à jour avec succès',
                'devis_product' => $devisProduct
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $devisProduct = DevisProduct::findOrFail($id);
            $devis = $devisProduct->devis;
            
            $devisProduct->delete();
            
            // Update devis totals after deletion
            $devis->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
} 