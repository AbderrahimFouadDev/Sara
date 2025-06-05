<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Product;
use App\Models\FactureProduct;
use Illuminate\Http\Request;

class FactureProductController extends Controller
{
    public function store(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_ht' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Calculate amounts
        $tva_rate = 20.00; // 20%
        $price_ht = $validated['price_ht'];
        $quantity = $validated['quantity'];
        $total_ht = $price_ht * $quantity;
        $tva_amount = $total_ht * ($tva_rate / 100);
        $total_ttc = $total_ht + $tva_amount;

        // Create or update the pivot record
        $pivotData = [
            'quantity' => $quantity,
            'price_ht' => $price_ht,
            'tva_rate' => $tva_rate,
            'tva_amount' => $tva_amount,
            'total_ttc' => $total_ttc
        ];

        // Check if product already exists in the facture
        if ($facture->products()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit existe déjà dans la facture'
            ], 422);
        }

        $facture->products()->attach($product->id, $pivotData);

        // Update facture totals
        $this->updateFactureTotals($facture);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté avec succès',
            'product' => [
                'id' => $product->id,
                'name' => $product->nom,
                'description' => $product->description,
                'quantity' => $quantity,
                'price_ht' => $price_ht,
                'tva_amount' => $tva_amount,
                'total_ttc' => $total_ttc
            ]
        ]);
    }

    public function update(Request $request, Facture $facture, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $pivotRecord = $facture->products()->findOrFail($product->id);
        $price_ht = $pivotRecord->pivot->price_ht;
        
        // Recalculate amounts
        $tva_rate = 20.00;
        $quantity = $validated['quantity'];
        $total_ht = $price_ht * $quantity;
        $tva_amount = $total_ht * ($tva_rate / 100);
        $total_ttc = $total_ht + $tva_amount;

        // Update the pivot record
        $facture->products()->updateExistingPivot($product->id, [
            'quantity' => $quantity,
            'tva_amount' => $tva_amount,
            'total_ttc' => $total_ttc
        ]);

        // Update facture totals
        $this->updateFactureTotals($facture);

        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour avec succès'
        ]);
    }

    public function destroy(Facture $facture, Product $product)
    {
        $facture->products()->detach($product->id);
        
        // Update facture totals
        $this->updateFactureTotals($facture);

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ]);
    }

    private function updateFactureTotals(Facture $facture)
    {
        $totals = $facture->products()->get()->reduce(function ($carry, $product) {
            $carry['montant_ht'] += $product->pivot->price_ht * $product->pivot->quantity;
            $carry['montant_tva'] += $product->pivot->tva_amount;
            $carry['montant_ttc'] += $product->pivot->total_ttc;
            return $carry;
        }, ['montant_ht' => 0, 'montant_tva' => 0, 'montant_ttc' => 0]);

        $facture->update($totals);
    }
} 