<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Models\Remboursement;

class ClientController extends Controller
{
    
public function store(Request $request)
{
    $data = $request->validate([
        'client_actif'      => 'sometimes|boolean',
        'client_name'       => 'required|string|max:255',
        'has_tin'           => 'sometimes|boolean',
        'tin'               => 'nullable|string|max:50',
        'autre_id'          => 'nullable|string|max:100',
        'points'            => 'nullable|integer|min:0',
        'solde'             => 'nullable|numeric|min:0',
        'contact_personne'  => 'nullable|string|max:255',
        'phone_code'        => 'nullable|string|max:5',
        'telephone'         => 'nullable|string|max:20',
        'fax_code'          => 'nullable|string|max:5',
        'fax'               => 'nullable|string|max:20',
        'email'             => 'nullable|email|max:255',
        'website'           => 'nullable|url|max:255',
        'linkedin'          => 'nullable|url|max:255',
        'facebook'          => 'nullable|url|max:255',
        'twitter'           => 'nullable|url|max:255',
        'google'            => 'nullable|url|max:255',
        'adresse'           => 'nullable|string|max:255',
        'complement'        => 'nullable|string|max:255',
        'adresse_sup'       => 'nullable|string|max:255',
        'immeuble'          => 'nullable|string|max:50',
        'region'            => 'nullable|string|max:100',
        'district'          => 'nullable|string|max:100',
        'ville'             => 'nullable|string|max:100',
        'code_postal'       => 'nullable|string|max:20',
        'pays'              => 'nullable|string|max:100',
    ]);

    Client::create($data);

    return redirect()
        ->back()
        ->with('success', 'Client ajouté avec succès!');
}

public function index(Request $request)
{
    $query = Client::query();
    
    // Apply filters
    switch ($request->filter) {
        case 'actif':
            $query->where('client_actif', true);
            break;
        case 'inactif':
            $query->where('client_actif', false);
            break;
        case 'equilibre':
            $query->where('solde', '=', 0);
            break;
        case 'deseq':
            $query->where('solde', '!=', 0);
            break;
        case 'credit':
            $query->where('solde', '>', 0);
            break;
        // 'tous' or no filter shows all clients
    }

    $clients = $query->orderBy('created_at', 'desc')->get();
    return view('customer.components.clients.liste_clients', compact('clients'));
}
// app/Http/Controllers/ClientController.php
public function search(Request $request)
{
    $q = $request->get('q','');
    $clients = Client::where('client_name','like',"%{$q}%")
                     ->select('id','client_name')
                     ->limit(10)
                     ->get();
    return response()->json($clients);
}

public function loadComponent($view, Request $request)
{
    switch($view) {
        case 'clients/liste_clients':
            $query = Client::query();
    
            // Apply filters
            switch ($request->filter) {
                case 'actif':
                    $query->where('client_actif', true);
                    break;
                case 'inactif':
                    $query->where('client_actif', false);
                    break;
                case 'equilibre':
                    $query->where('solde', '=', 0);
                    break;
                case 'deseq':
                    $query->where('solde', '!=', 0);
                    break;
                case 'credit':
                    $query->where('solde', '>', 0);
                    break;
                // 'tous' or no filter shows all clients
            }
            
            $clients = $query->orderBy('created_at', 'desc')->get();
            return view('customer.components.clients.liste_clients', compact('clients'));
            
        case 'clients/historique_remboursements':
            $query = Remboursement::with(['client', 'facture']);
            
            // Apply filters if any
            if ($request->has('search')) {
                $query->whereHas('facture', function($q) use ($request) {
                    $q->where('numero', 'like', '%' . $request->search . '%');
                });
            }
            
            if ($request->has('statut')) {
                $query->where('statut', $request->statut);
            }
            
            if ($request->has('date')) {
                $query->whereDate('date_remboursement', $request->date);
            }
            
            $remboursements = $query->orderBy('date_remboursement', 'desc')->paginate(10);
            return view('customer.components.clients.historique_remboursements', compact('remboursements'));
            
        case 'liste_fournisseurs':
            $fournisseurs = Fournisseur::orderBy('created_at', 'desc')->get();
            return view('customer.components.fournisseurs.liste_fournisseurs', compact('fournisseurs'));
            
        case 'devis/creer_devis':
            return view('customer.components.devis.creer_devis');
            
        case 'devis/liste_devis':
            return app(DevisController::class)->loadComponent($view, $request);
            
        case 'factures/creer_facture':
            return view('customer.components.factures.creer_facture');
            
        case 'factures/liste_factures':
            return app(FactureController::class)->loadComponent($view, $request);
            
        case 'clients/ajouter_client':
            return view('customer.components.clients.ajouter_client');

        case 'clients/edit_client':
            $clientId = $request->query('clientId');
            if (!$clientId) {
                abort(400, 'Client ID is required');
            }
            $client = Client::findOrFail($clientId);
            return view('customer.components.clients.edit_client', compact('client'));
            
        case 'clients/voir_client':
            $clientId = $request->query('clientId');
            if (!$clientId) {
                abort(400, 'Client ID is required');
            }
            $client = Client::findOrFail($clientId);
            return view('customer.components.clients.voir_client', compact('client'));
            
        default:
            return view("customer.components.{$view}");
    }
}

public function export(Request $request)
{
    $format = $request->get('format', 'csv');
    $clients = Client::all();
    
    $headers = [
        'ID',
        'Date de création',
        'Date de modification',
        'Statut',
        'Nom du client',
        'TIN/TVA',
        'Numéro TIN',
        'Autre ID',
        'Points',
        'Solde',
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
    
    $callback = function() use ($clients, $headers) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headers);
        
        foreach ($clients as $client) {
            fputcsv($file, [
                $client->id,
                $client->created_at->format('d/m/Y H:i:s'),
                $client->updated_at->format('d/m/Y H:i:s'),
                $client->client_actif ? 'Actif' : 'Inactif',
                $client->client_name,
                $client->has_tin ? 'Oui' : 'Non',
                $client->tin,
                $client->autre_id,
                $client->points,
                $client->solde,
                $client->contact_personne,
                $client->phone_code,
                $client->telephone,
                $client->fax_code,
                $client->fax,
                $client->email,
                $client->website,
                $client->linkedin,
                $client->facebook,
                $client->twitter,
                $client->google,
                $client->adresse,
                $client->complement,
                $client->adresse_sup,
                $client->immeuble,
                $client->region,
                $client->district,
                $client->ville,
                $client->code_postal,
                $client->pays
            ]);
        }
        
        fclose($file);
    };
    
    $filename = 'clients_' . date('Y-m-d') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    return response()->stream($callback, 200, $headers);
}

public function edit(Client $client)
{
    return view('customer.components.edit_client', compact('client'));
}

public function update(Request $request, Client $client)
{
    $data = $request->validate([
        'client_actif'      => 'sometimes|boolean',
        'client_name'       => 'required|string|max:255',
        'has_tin'           => 'sometimes|boolean',
        'tin'               => 'nullable|string|max:50',
        'autre_id'          => 'nullable|string|max:100',
        'points'            => 'nullable|integer|min:0',
        'solde'             => 'nullable|numeric|min:0',
        'contact_personne'  => 'nullable|string|max:255',
        'phone_code'        => 'nullable|string|max:5',
        'telephone'         => 'nullable|string|max:20',
        'fax_code'          => 'nullable|string|max:5',
        'fax'               => 'nullable|string|max:20',
        'email'             => 'nullable|email|max:255',
        'website'           => 'nullable|url|max:255',
        'linkedin'          => 'nullable|url|max:255',
        'facebook'          => 'nullable|url|max:255',
        'twitter'           => 'nullable|url|max:255',
        'google'            => 'nullable|url|max:255',
        'adresse'           => 'nullable|string|max:255',
        'complement'        => 'nullable|string|max:255',
        'adresse_sup'       => 'nullable|string|max:255',
        'immeuble'          => 'nullable|string|max:50',
        'region'            => 'nullable|string|max:100',
        'district'          => 'nullable|string|max:100',
        'ville'             => 'nullable|string|max:100',
        'code_postal'       => 'nullable|string|max:20',
        'pays'              => 'nullable|string|max:100',
    ]);

    $client->update($data);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Client modifié avec succès!'
        ]);
    }

    return redirect()
        ->route('clients.index')
        ->with('success', 'Client modifié avec succès!');
}

/**
 * DELETE /clients/{client}
 */
public function destroy(Client $client)
{
    try {
        $client->delete();
        return response()->json([
            'success' => true,
            'message' => 'Client supprimé avec succès'
        ]);
    } catch (\Exception $e) {
        // Check if it's a foreign key constraint violation
        if (str_contains($e->getMessage(), 'foreign key constraint fails') || $e->getCode() === '23000') {
            return response()->json([
                'success' => false,
                'error_type' => 'foreign_key_constraint',
                'message' => 'Ce client ne peut pas être supprimé car il est lié à des documents existants.'
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la suppression du client.'
        ], 500);
    }
}

}
