<?php

namespace App\Http\Controllers;

use App\Models\BonLivraisonProduct;
use App\Models\BonLivraison;
use App\Models\Product;
use Illuminate\Http\Request;

class BonLivraisonProductController extends Controller
{
    public function store(Request $request, BonLivraison $bonLivraison)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_ht' => 'required|numeric|min:0',
            'total_ht' => 'required|numeric|min:0',
            'tva_rate' => 'required|numeric',
            'tva_amount' => 'required|numeric|min:0',
            'total_ttc' => 'required|numeric|min:0'
        ]);

        $product = BonLivraisonProduct::create([
            'bon_livraison_id' => $bonLivraison->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price_ht' => $validated['price_ht'],
            'total_ht' => $validated['total_ht'],
            'tva_rate' => $validated['tva_rate'],
            'tva_amount' => $validated['tva_amount'],
            'total_ttc' => $validated['total_ttc']
        ]);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function update(Request $request, BonLivraisonProduct $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price_ht' => 'required|numeric|min:0',
            'total_ht' => 'required|numeric|min:0',
            'tva_rate' => 'required|numeric',
            'tva_amount' => 'required|numeric|min:0',
            'total_ttc' => 'required|numeric|min:0'
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function destroy(BonLivraisonProduct $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ]);
    }
} 