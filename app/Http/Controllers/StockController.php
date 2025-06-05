<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    public function loadComponent($view, $data = [])
    {
        // Get common data used across multiple views
        $categories = $data['categories'] ?? Category::all();
        $fournisseurs = $data['fournisseurs'] ?? Fournisseur::orderBy('fournisseur_name')->get();
        
        // Remove 'stock/' prefix if present
        $view = str_replace('stock/', '', $view);
        
        // Check if the view contains a category ID (for edit_categorie)
        $categoryId = null;
        if (preg_match('/^edit_categorie\/([0-9]+)$/', $view, $matches)) {
            $categoryId = $matches[1];
            $view = 'edit_categorie';
        }

        // Map view names to their actual paths
        $viewMap = [
            'catalogue' => 'customer.components.stock.liste_produits',
            'form_produit' => 'customer.components.stock.form_produit',
            'liste_produits' => 'customer.components.stock.liste_produits',
            'alertes_stock' => 'customer.components.stock.alertes_stock',
            'mouvements_stock' => 'customer.components.stock.mouvements_stock',
            'entrepots' => 'customer.components.stock.entrepots',
            'form_entrepot' => 'customer.components.stock.form_entrepot',
            'inventaire' => 'customer.components.stock.inventaire',
            'alertes' => 'customer.components.stock.alertes_stock',
            'ajouter_categorie' => 'customer.components.stock.ajouter_categorie',
            'liste_categories' => 'customer.components.stock.liste_categories',
            'edit_categorie' => 'customer.components.stock.edit_categorie',
        ];

        // Get the actual view path from the map, or use the original view name
        $viewPath = $viewMap[$view] ?? 'customer.components.stock.' . $view;

        // Check if the view exists
        if (!view()->exists($viewPath)) {
            return response()->json([
                'message' => "View [{$viewPath}] not found.",
                'status' => 'error'
            ], 404);
        }

        // Handle different views
        switch($view) {
            case 'form_produit':
                return view($viewPath, compact('fournisseurs', 'categories'));

            case 'liste_produits':
                $query = Product::with(['categorie', 'fournisseur']);
                
                if ($data->has('type')) {
                    $type = $data->type;
                    if ($type === 'article') {
                        $query->where('is_service', false);
                    } elseif ($type === 'service') {
                        $query->where('is_service', true);
                    }
                }
                
                if ($data->has('category') && $data->category !== 'all') {
                    $query->where('categorie_id', $data->category);
                }
                
                if ($data->has('search')) {
                    $search = $data->search;
                    $query->where(function($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                    });
                }
                
                $products = $query->orderBy('created_at', 'desc')->get();
                return view($viewPath, compact('fournisseurs', 'categories', 'products'));

            case 'edit_produit':
                if (!$data->has('id')) {
                    return response()->json([
                        'error' => 'Product ID is required'
                    ], 400);
                }
                
                $product = Product::with(['categorie', 'fournisseur'])->find($data->id);
                
                if (!$product) {
                    return response()->json([
                        'error' => 'Product not found'
                    ], 404);
                }
                
                return view($viewPath, compact('fournisseurs', 'categories', 'product'));

            case 'edit_categorie':
                if ($categoryId) {
                    $category = Category::findOrFail($categoryId);
                    return view($viewPath, compact('category', 'categories'));
                }
                return response()->json([
                    'message' => "Category ID is required for edit view.",
                    'status' => 'error'
                ], 400);
                
            case 'liste_categories':
                return view($viewPath, compact('categories'));

            default:
                // For any other view, pass the common variables
                return view($viewPath, compact('fournisseurs', 'categories'));
        }
    }

   
    public function indexCategory(Request $request)
{
    $categories = Category::orderBy('created_at', 'desc')->get();

    return view('customer.components.category.liste_categories', compact('categories'));
}


    public function storeCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $category = Category::create($validated);
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating category'], 500);
        }
    }



    public function getProduct($id)
    {
        try {
            $product = Product::with(['categorie', 'fournisseur'])->findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'categorie_id' => 'nullable|exists:categories,id',
                'fournisseur_id' => 'nullable|exists:fournisseurs,id',
                'prix_achat' => 'required|numeric|min:0',
                'prix_vente' => 'required|numeric|min:0',
                'quantite' => 'required|numeric|min:0',
                'is_service' => 'sometimes|boolean',
                'quantite_min' => 'nullable|integer|min:0',
                'code_barre' => 'nullable|string|max:100',
                'reference' => 'nullable|string|max:100',
                'unite' => 'nullable|string|max:50'
            ]);

            $product->update($data);
            $updatedProduct = $product->fresh(['categorie', 'fournisseur']);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit modifié avec succès!',
                    'product' => $updatedProduct
                ]);
            }

            // Get the data needed for the list view
            $categories = Category::all();
            $fournisseurs = Fournisseur::orderBy('fournisseur_name')->get();
            $products = Product::with(['categorie', 'fournisseur'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('customer.components.stock.liste_produits', 
                compact('categories', 'fournisseurs', 'products'))
                ->with('success', 'Produit modifié avec succès!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du produit: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erreur lors de la modification du produit']);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productName = $product->nom;
            $product->delete();
            
            // Get data for list view
            $categories = Category::all();
            $fournisseurs = Fournisseur::orderBy('fournisseur_name')->get();
            $products = Product::with(['categorie', 'fournisseur'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('customer.components.stock.liste_produits',
                compact('categories', 'fournisseurs', 'products'))
                ->with('success', "Le produit '{$productName}' a été supprimé avec succès!");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression du produit']);
        }
    }

    // First deleteCategory method removed to fix duplicate method declaration

    public function editCategory(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $categories = Category::all();
            return view('customer.components.stock.edit_categorie', compact('category', 'categories'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Catégorie non trouvée']);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $category = Category::findOrFail($id);
            $category->update($validated);
            
            if ($request->ajax()) {
                // If it's an AJAX request, return JSON response
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès!'
                ]);
            }
            
            // For regular form submission, redirect to dashboard with liste_categories component
            return redirect()->route('customer.dashboard')
                ->with('component', 'stock/liste_categories')
                ->with('success', 'Catégorie modifiée avec succès!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification de la catégorie']);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            
            return redirect()->route('customer.dashboard')
                ->with('component', 'stock/liste_categories')
                ->with('success', 'Catégorie supprimée avec succès!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression de la catégorie']);
        }
    }

    public function indexProduct(Request $request)
    {
        $products = Product::with(['categorie', 'fournisseur'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.components.stock.liste_produits', compact('products'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'nom_article'    => 'required|string|max:255',
            'is_service'     => 'sometimes|boolean',
            'description'    => 'nullable|string',
            'categorie_id'   => 'nullable|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'quantite'       => 'nullable|integer|min:0',
            'prix_achat'     => 'nullable|numeric|min:0',
            'prix_vente'     => 'nullable|numeric|min:0',
        ]);

        // Map the field names from the form to your Product model
        $data = [
            'nom'           => $validated['nom_article'],
            'is_service'    => $request->has('is_service'),
            'description'   => $validated['description'] ?? null,
            'categorie_id'  => $validated['categorie_id'] ?? null,
            'fournisseur_id'=> $validated['fournisseur_id'] ?? null,
            'quantite'      => $validated['quantite'] ?? 0,
            'prix_achat'    => $validated['prix_achat'] ?? 0,
            'prix_vente'    => $validated['prix_vente'] ?? 0,
        ];

        $product = Product::create($data);

        // Return JSON if AJAX, or redirect if normal form post
        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()
            ->route('stock.liste_produits')
            ->with('success', 'Produit ajouté avec succès !');
    }
} 