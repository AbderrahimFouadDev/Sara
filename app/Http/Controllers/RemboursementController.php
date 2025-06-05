<?php

namespace App\Http\Controllers;

use App\Models\Remboursement;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemboursementController extends Controller
{
    /**
     * Display the reimbursement history for the authenticated client.
     */
    public function index()
    {
        $remboursements = Remboursement::forClient(Auth::id())
            ->with(['facture'])
            ->orderBy('date_remboursement', 'desc')
            ->paginate(10);

        return view('customer.components.historique_remboursements', compact('remboursements'));
    }

    /**
     * Get reimbursement details.
     */
    public function show(Remboursement $remboursement)
    {
        // Check if the authenticated user owns this reimbursement
        if ($remboursement->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'remboursement' => $remboursement->load('facture')
        ]);
    }

    /**
     * Filter reimbursements based on search criteria.
     */
    public function filter(Request $request)
    {
        $query = Remboursement::forClient(Auth::id());

        // Apply status filter
        if ($request->filled('statut')) {
            $query->byStatus($request->statut);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('date_remboursement', $date);
        }

        // Apply facture number search
        if ($request->filled('search')) {
            $query->whereHas('facture', function($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->search . '%');
            });
        }

        $remboursements = $query->with('facture')
            ->orderBy('date_remboursement', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('customer.components.partials.remboursements_table', compact('remboursements'))->render()
            ]);
        }

        return view('customer.components.historique_remboursements', compact('remboursements'));
    }

    /**
     * Validate that reimbursement amount doesn't exceed invoice amount.
     */
    private function validateRemboursementAmount($factureId, $montant)
    {
        $facture = Facture::findOrFail($factureId);
        
        // Get total amount already reimbursed for this invoice
        $totalRembourse = Remboursement::where('facture_id', $factureId)
            ->where('statut', 'termine')
            ->sum('montant');
            
        // Check if new reimbursement amount would exceed invoice amount
        if (($totalRembourse + $montant) > $facture->montant_total) {
            return false;
        }
        
        return true;
    }
} 