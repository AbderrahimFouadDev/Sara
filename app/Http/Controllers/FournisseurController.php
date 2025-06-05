<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fournisseur_name' => 'required|string|max:255',
                'fournisseur_actif' => 'boolean',
                'has_tin' => 'boolean',
                'tin' => 'nullable|string|max:255',
                'autre_id_vendeur' => 'nullable|string|max:255',
                'debut_balance_fournisseur' => 'nullable|numeric',
                'contact_personne' => 'nullable|string|max:255',
                'phone_code' => 'nullable|string|max:5',
                'telephone' => 'nullable|string|max:20',
                'fax_code' => 'nullable|string|max:5',
                'fax' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'google' => 'nullable|url|max:255',
                'adresse' => 'nullable|string|max:255',
                'complement' => 'nullable|string|max:255',
                'adresse_sup' => 'nullable|string|max:255',
                'immeuble' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:255',
                'code_postal' => 'nullable|string|max:20',
                'pays' => 'nullable|string|max:255',
            ]);

            $fournisseur = Fournisseur::create($validated);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Fournisseur ajouté avec succès']);
            }

            return back()->with('success', 'Fournisseur ajouté avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Une erreur est survenue lors de l\'enregistrement'
                ], 500);
            }
            throw $e;
        }
    }

    public function index()
    {
        $fournisseurs = Fournisseur::orderBy('created_at', 'desc')->get();
        return view('customer.components.fournisseurs.liste_fournisseurs', compact('fournisseurs'));
    }

    public function loadComponent($view, Request $request)
    {
        switch($view) {
            case 'fournisseurs/liste_fournisseurs':
                $query = Fournisseur::query();
                
                // Apply filters
                switch ($request->get('filter', 'tous')) {
                    case 'actif':
                        $query->where('fournisseur_actif', true);
                        break;
                    case 'inactif':
                        $query->where('fournisseur_actif', false);
                        break;
                    case 'equilibre':
                        $query->where('debut_balance_fournisseur', 0);
                        break;
                    case 'deseq':
                        $query->where('debut_balance_fournisseur', '!=', 0);
                        break;
                    // 'tous' doesn't need any filter
                }
                
                $fournisseurs = $query->orderBy('created_at', 'desc')->get();
                return view('customer.components.fournisseurs.liste_fournisseurs', compact('fournisseurs'));

            case 'fournisseurs/ajouter_fournisseur':
                return view('customer.components.fournisseurs.ajouter_fournisseur');

            case 'fournisseurs/voir_fournisseur':
                $fournisseurId = $request->query('id');
                if (!$fournisseurId) {
                    abort(400, 'Fournisseur ID is required');
                }
                $fournisseur = Fournisseur::findOrFail($fournisseurId);
                return view('customer.components.fournisseurs.voir_fournisseur', compact('fournisseur'));

            case 'fournisseurs/edit_fournisseur':
            $fournisseurId = $request->query('id');
            if (!$fournisseurId) {
                abort(400, 'Fournisseur ID is required');
            }
            $fournisseur = Fournisseur::findOrFail($fournisseurId);
                return view('customer.components.fournisseurs.edit_fournisseur', compact('fournisseur'));

            default:
                // Extract the view name without the prefix
                $viewPath = str_replace('fournisseurs/', '', $view);
                return view("customer.components.fournisseurs.{$viewPath}");
        }
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $fournisseurs = Fournisseur::all();
        
        $headers = [
            'ID',
            'Date de création',
            'Date de modification',
            'Statut',
            'Nom du fournisseur',
            'TIN/TVA',
            'Numéro TIN',
            'ID Vendeur',
            'Balance initiale',
            'Personne de contact',
            'Code téléphone',
            'Téléphone',
            'Code fax',
            'Fax',
            'Email',
            'Site web',
            'LinkedIn',
            'Facebook',
            'Twitter',
            'Google',
            'Adresse',
            'Complément',
            'Adresse supplémentaire',
            'Immeuble',
            'Région',
            'District',
            'Ville',
            'Code postal',
            'Pays'
        ];
        
        $callback = function() use ($fournisseurs, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($fournisseurs as $fournisseur) {
                fputcsv($file, [
                    $fournisseur->id,
                    $fournisseur->created_at->format('d/m/Y H:i:s'),
                    $fournisseur->updated_at->format('d/m/Y H:i:s'),
                    $fournisseur->fournisseur_actif ? 'Actif' : 'Inactif',
                    $fournisseur->fournisseur_name,
                    $fournisseur->has_tin ? 'Oui' : 'Non',
                    $fournisseur->tin,
                    $fournisseur->autre_id_vendeur,
                    $fournisseur->debut_balance_fournisseur,
                    $fournisseur->contact_personne,
                    $fournisseur->phone_code,
                    $fournisseur->telephone,
                    $fournisseur->fax_code,
                    $fournisseur->fax,
                    $fournisseur->email,
                    $fournisseur->website,
                    $fournisseur->linkedin,
                    $fournisseur->facebook,
                    $fournisseur->twitter,
                    $fournisseur->google,
                    $fournisseur->adresse,
                    $fournisseur->complement,
                    $fournisseur->adresse_sup,
                    $fournisseur->immeuble,
                    $fournisseur->region,
                    $fournisseur->district,
                    $fournisseur->ville,
                    $fournisseur->code_postal,
                    $fournisseur->pays
                ]);
            }
            
            fclose($file);
        };
        
        $filename = 'fournisseurs_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $headers);
    }

    public function details($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return view('customer.components.fournisseurs.voir_fournisseur', compact('fournisseur'));
    }

    public function update(Request $request, $id)
    {
        try {
            $fournisseur = Fournisseur::findOrFail($id);
            
            $validated = $request->validate([
                'fournisseur_name' => 'required|string|max:255',
                'fournisseur_actif' => 'boolean',
                'has_tin' => 'boolean',
                'tin' => 'nullable|string|max:255',
                'autre_id_vendeur' => 'nullable|string|max:255',
                'debut_balance_fournisseur' => 'nullable|numeric',
                'contact_personne' => 'nullable|string|max:255',
                'phone_code' => 'nullable|string|max:5',
                'telephone' => 'nullable|string|max:20',
                'fax_code' => 'nullable|string|max:5',
                'fax' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'google' => 'nullable|url|max:255',
                'adresse' => 'nullable|string|max:255',
                'complement' => 'nullable|string|max:255',
                'adresse_sup' => 'nullable|string|max:255',
                'immeuble' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:255',
                'code_postal' => 'nullable|string|max:20',
                'pays' => 'nullable|string|max:255',
            ]);

            $fournisseur->update($validated);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Fournisseur mis à jour avec succès']);
            }

            return back()->with('success', 'Fournisseur mis à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Une erreur est survenue lors de la mise à jour'
                ], 500);
            }
            throw $e;
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $fournisseurs = Fournisseur::where('fournisseur_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();
        return response()->json($fournisseurs);
    }
} 