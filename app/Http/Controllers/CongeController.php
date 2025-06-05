<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Salarie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CongeController extends Controller
{
    public function index()
    {
        $conges = Conge::with('salarie')->latest()->get();
        
        $stats = [
            'totalConges' => Conge::where('status', 'approved')->sum('duree'),
            'enConge' => Conge::enCours()->count(),
            'enAttente' => Conge::enAttente()->count(),
            'soldeMoyen' => 0 // You'll need to implement this based on your business logic
        ];

        return view('customer.components.rh.conges', compact('conges', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'salarie_id' => 'required',
            'type' => 'required|in:paid,sick,unpaid,other',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'motif' => 'required'
        ]);

        Conge::create($validated);

        return redirect('/dashboard/component/rh/conges');
    }

    public function show(Conge $conge)
    {
        $conge->load('salarie', 'approvedByUser');
        return response()->json($conge);
    }

    /**
     * Update the status of the specified leave request.
     */
    public function updateStatus(Request $request, Conge $conge)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $conge->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // Flash a success message
        session()->flash('success', $validated['status'] === 'approved' ? 'Congé approuvé avec succès' : 'Congé refusé avec succès');

        // Redirect back to the conges list
        return redirect()->route('rh.component', ['view' => 'conges']);
    }

    public function destroy(Conge $conge)
    {
        if ($conge->status === 'approved') {
            return response()->json([
                'message' => 'Impossible de supprimer un congé approuvé'
            ], 403);
        }

        $conge->delete();

        return response()->json([
            'message' => 'Demande de congé supprimée avec succès'
        ]);
    }

    public function export()
    {
        // Implement export logic here
        return response()->json([
            'message' => 'Export functionality to be implemented'
        ]);
    }

    public function calendar()
    {
        $month = request('month', Carbon::now()->month);
        $year = request('year', Carbon::now()->year);

        $conges = Conge::with('salarie')
            ->whereYear('date_debut', $year)
            ->whereMonth('date_debut', $month)
            ->orWhere(function($query) use ($month, $year) {
                $query->whereYear('date_fin', $year)
                    ->whereMonth('date_fin', $month);
            })
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'conges' => $conges,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function filter(Request $request)
    {
        \Log::info('Filter request received:', $request->all());
        
        $query = Conge::with('salarie');

        // Filter by status
        if ($request->filled('filter') && $request->filter !== 'tous') {
            \Log::info('Filtering by status:', ['filter' => $request->filter]);
            $query->where('status', $request->filter);
        }

        // Search by employee name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            \Log::info('Searching for:', ['search' => $search]);
            $query->whereHas('salarie', function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('nom_complet', 'like', "%{$search}%")
                          ->orWhere('matricule', 'like', "%{$search}%");
                });
            });
        }

        $conges = $query->latest()->get();
        \Log::info('Found leaves:', ['count' => $conges->count()]);
        
        if ($request->wantsJson()) {
            return response()->json(['conges' => $conges]);
        }

        $stats = [
            'totalConges' => Conge::where('status', 'approved')->sum('duree'),
            'enConge' => Conge::enCours()->count(),
            'enAttente' => Conge::enAttente()->count(),
            'soldeMoyen' => 18
        ];

        return view('customer.components.rh.conges', compact('conges', 'stats'));
    }

    public function loadComponent($view, Request $request)
    {
        \Log::info('Loading conge component:', ['view' => $view, 'filter' => $request->filter]);
        
        switch($view) {
            case 'rh/conges':
                $query = Conge::with('salarie');
                
                // Apply filters
                switch ($request->filter) {
                    case 'approved':
                        $query->where('status', 'approved');
                        break;
                    case 'pending':
                        $query->where('status', 'pending');
                        break;
                    case 'rejected':
                        $query->where('status', 'rejected');
                        break;
                    // 'tous' or no filter shows all conges
                }
                
                // Apply search if present
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->whereHas('salarie', function($q) use ($search) {
                        $q->where('nom_complet', 'like', "%{$search}%")
                          ->orWhere('matricule', 'like', "%{$search}%");
                    });
                }
                
                $conges = $query->latest()->get();
                
                $stats = [
                    'totalConges' => Conge::where('status', 'approved')->sum('duree'),
                    'enConge' => Conge::enCours()->count(),
                    'enAttente' => Conge::enAttente()->count(),
                    'soldeMoyen' => 18
                ];
                
                return view('customer.components.rh.conges', compact('conges', 'stats'));
                
            default:
                return view("customer.components.{$view}");
        }
    }
} 