<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salarie;
use Illuminate\Support\Facades\Storage;
use App\Models\Conge;

class RhController extends Controller
{
    protected $viewMap = [
            'salaries' => 'customer.components.rh.salaries',
            'ajouter_salarie' => 'customer.components.rh.ajouter_salarie',
            'form_salarie' => 'customer.components.rh.form_salarie',
            'voir_salarie' => 'customer.components.rh.voir_salarie',
        'edit_salarie' => 'customer.components.rh.edit_salarie',
            'bulletin_paie' => 'customer.components.rh.bulletin_paie',
            'bulletins' => 'customer.components.rh.bulletins',
            'conges' => 'customer.components.rh.conges',
            'contrats' => 'customer.components.rh.contrats'
        ];

    public function loadComponent($view, Request $request)
    {
        \Log::info('Loading component', ['view' => $view, 'request' => $request->all()]);
        
        // Extract any parameters passed with the request
        $params = $request->all();

        // Handle specific views that need data
        if ($view === 'rh/voir_conge_details') {
            $conge = \App\Models\Conge::with(['salarie', 'approvedByUser'])->findOrFail($params['conge_id']);
            return view('customer.components.' . $view, compact('conge'));
        }

        // Remove 'rh/' prefix if present and store it
        $prefix = '';
        if (strpos($view, 'rh/') === 0) {
            $prefix = 'rh/';
            $view = str_replace('rh/', '', $view);
            \Log::info('Processed view name', ['view' => $view, 'prefix' => $prefix]);
        }

        // Get the actual view path from the map, or use the original view name
        $viewPath = $this->viewMap[$view] ?? ($prefix . $view);
        \Log::info('View path resolved', ['viewPath' => $viewPath]);
        
        // For voir_salarie and edit_salarie views, we need to fetch the employee data
        if (in_array($view, ['voir_salarie', 'edit_salarie'])) {
            $employeeId = $request->input('employeeId');
            $salarie = Salarie::findOrFail($employeeId);
            return view($viewPath, compact('salarie'));
        }

        // Check if the view exists
        if (!view()->exists($viewPath)) {
            // Try with customer.components prefix
            $altViewPath = 'customer.components.' . $viewPath;
            if (!view()->exists($altViewPath)) {
                return response()->json([
                    'message' => "View [{$viewPath}] not found.",
                    'status' => 'error'
                ], 404);
            }
            $viewPath = $altViewPath;
        }

        // Prepare data based on the view
        $data = [];
        
        if ($view === 'salaries') {
            $salaries = Salarie::orderBy('created_at', 'desc')->get();
            $data = [
                'salaries' => $salaries,
                'totalEmployees' => $salaries->count(),
                'activeEmployees' => $salaries->where('statut', 'actif')->count(),
                'onLeave' => $salaries->where('statut', 'congé')->count(),
                'quitEmployees' => $salaries->where('statut', 'quitté')->count(),
                'totalPayroll' => $salaries->sum('salaire_base')
            ];
        }

        // Handle other specific views
        switch ($view) {
            case 'conges':
                $conges = \App\Models\Conge::with('salarie')->latest()->get();
                $stats = [
                    'totalConges' => \App\Models\Conge::where('status', 'approved')->sum('duree'),
                    'enConge' => \App\Models\Conge::whereDate('date_debut', '<=', now())
                        ->whereDate('date_fin', '>=', now())
                        ->where('status', 'approved')
                        ->count(),
                    'enAttente' => \App\Models\Conge::where('status', 'pending')->count(),
                    'soldeMoyen' => 0
                ];
                return view($viewPath, compact('conges', 'stats'));

            case 'nouveau_conge':
                $salaries = Salarie::orderBy('nom_complet')->get();
                return view($viewPath, compact('salaries'));
            
            default:
                return view($viewPath, $data ?? []);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'nom_complet' => 'required|string|max:255',
                'cin' => 'required|string|unique:salaries,cin',
                'cnss' => 'nullable|string',
                'poste' => 'required|string',
                'departement' => 'required|string',
                'statut' => 'required|string|in:actif,congé,quitté',
                'date_embauche' => 'required|date',
                'salaire_base' => 'required|numeric|min:0',
                'type_contrat' => 'required|string',
                'date_debut_contrat' => 'required|date',
                'date_fin_contrat' => 'nullable|date|after:date_debut_contrat',
                'document_cin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'document_cnss' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'document_contrat' => 'nullable|file|mimes:pdf|max:2048',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Create storage directories if they don't exist
            $directories = [
                'salarie-photos',
                'documents/salaries/cin',
                'documents/salaries/cnss',
                'documents/salaries/contrats'
            ];

            foreach ($directories as $dir) {
                if (!Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->makeDirectory($dir);
                }
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                \Log::info('Processing photo upload');
                $file = $request->file('photo');
                
                if ($file->isValid()) {
                    $filename = 'photo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('salarie-photos', $filename, 'public');
                    
                    if ($path) {
                        $validated['photo'] = $path;
                        \Log::info('Photo uploaded successfully', ['path' => $path]);
                    } else {
                        throw new \Exception('Failed to store photo file');
                    }
                } else {
                    throw new \Exception('Invalid photo file uploaded');
                }
            }

            // Handle other document uploads
            foreach (['document_cin', 'document_cnss', 'document_contrat'] as $document) {
                if ($request->hasFile($document)) {
                    $file = $request->file($document);
                    if ($file->isValid()) {
                        $type = str_replace('document_', '', $document);
                        $filename = $type . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs("documents/salaries/{$type}", $filename, 'public');
                        
                        if ($path) {
                            $validated[$document] = $path;
                        } else {
                            throw new \Exception("Failed to store {$document} file");
                        }
                    }
                }
            }

            // Create the employee record
            $employee = Salarie::create($validated);

            return response()->json([
                'message' => 'Salarié ajouté avec succès',
                'employee' => $employee
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error storing employee:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du salarié: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Salarie $employee)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'cin' => 'required|string|unique:salaries,cin,' . $employee->id,
            'cnss' => 'nullable|string',
            'poste' => 'required|string',
            'departement' => 'required|string',
            'statut' => 'required|string|in:actif,congé,quitté',
            'date_embauche' => 'required|date',
            'salaire_base' => 'required|numeric|min:0',
            'type_contrat' => 'required|string',
            'date_debut_contrat' => 'required|date',
            'date_fin_contrat' => 'nullable|date|after:date_debut_contrat',
            'document_cin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_cnss' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_contrat' => 'nullable|file|mimes:pdf|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            
            $file = $request->file('photo');
            $filename = 'photo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('salarie-photos', $filename, 'public');
            $validated['photo'] = $path;
        }

        // Handle document uploads
        if ($request->hasFile('document_cin')) {
            if ($employee->document_cin) {
                Storage::disk('public')->delete($employee->document_cin);
            }
            $file = $request->file('document_cin');
            $filename = 'cin_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['document_cin'] = $file->storeAs('documents/salaries/cin', $filename, 'public');
        }

        if ($request->hasFile('document_cnss')) {
            if ($employee->document_cnss) {
                Storage::disk('public')->delete($employee->document_cnss);
            }
            $file = $request->file('document_cnss');
            $filename = 'cnss_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['document_cnss'] = $file->storeAs('documents/salaries/cnss', $filename, 'public');
        }

        if ($request->hasFile('document_contrat')) {
            if ($employee->document_contrat) {
                Storage::disk('public')->delete($employee->document_contrat);
            }
            $file = $request->file('document_contrat');
            $filename = 'contrat_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['document_contrat'] = $file->storeAs('documents/salaries/contrats', $filename, 'public');
        }

        $employee->update($validated);

        return response()->json([
            'message' => 'Salarié mis à jour avec succès',
            'employee' => $employee
        ]);
    }

    public function destroy(Salarie $employee)
    {
        // Delete associated files
        if ($employee->document_cin) {
            Storage::disk('public')->delete($employee->document_cin);
        }
        if ($employee->document_cnss) {
            Storage::disk('public')->delete($employee->document_cnss);
        }
        if ($employee->document_contrat) {
            Storage::disk('public')->delete($employee->document_contrat);
        }

        $employee->delete();

        return response()->json([
            'message' => 'Salarié supprimé avec succès'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $department = $request->get('department');
        $status = $request->get('status');

        $employees = Salarie::query();

        if ($query) {
            $employees->where(function($q) use ($query) {
                $q->where('nom_complet', 'like', "%{$query}%")
                  ->orWhere('cin', 'like', "%{$query}%")
                  ->orWhere('cnss', 'like', "%{$query}%");
            });
        }

        if ($department) {
            $employees->where('departement', $department);
        }

        if ($status) {
            $employees->where('statut', $status);
        }

        return response()->json([
            'employees' => $employees->get()
        ]);
    }

    public function export(Request $request)
    {
        try {
            $query = Salarie::query();

            // Apply filters if they exist
            if ($request->has('q')) {
                $searchQuery = $request->get('q');
                $query->where(function($q) use ($searchQuery) {
                    $q->where('nom_complet', 'like', "%{$searchQuery}%")
                      ->orWhere('cin', 'like', "%{$searchQuery}%")
                      ->orWhere('cnss', 'like', "%{$searchQuery}%");
                });
            }

            if ($request->has('department')) {
                $query->where('departement', $request->get('department'));
            }

            if ($request->has('status')) {
                $query->where('statut', $request->get('status'));
            }

            $salaries = $query->get();
            
            $headers = [
                'ID',
                'Nom Complet',
                'CIN',
                'CNSS',
                'Poste',
                'Département',
                'Statut',
                'Date d\'embauche',
                'Salaire Base',
                'Type Contrat',
                'Date Début Contrat',
                'Date Fin Contrat',
                'Jours Congés Restants',
                'Jours Absences Non Justifiées',
                'Dernier Net Payé',
                'Mois Dernier Payé',
                'Date de création',
                'Date de modification'
            ];
            
            $filename = 'salaries_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            // Add UTF-8 BOM for proper French character encoding in Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($output, $headers);
            
            // Write data rows
            foreach ($salaries as $salarie) {
                fputcsv($output, [
                    $salarie->id,
                    $salarie->nom_complet,
                    $salarie->cin,
                    $salarie->cnss,
                    $salarie->poste,
                    $salarie->departement,
                    $salarie->statut,
                    $salarie->date_embauche ? $salarie->date_embauche->format('d/m/Y') : '',
                    number_format($salarie->salaire_base, 2, ',', ' '),
                    $salarie->type_contrat,
                    $salarie->date_debut_contrat ? $salarie->date_debut_contrat->format('d/m/Y') : '',
                    $salarie->date_fin_contrat ? $salarie->date_fin_contrat->format('d/m/Y') : '',
                    $salarie->jours_conges_restants,
                    $salarie->jours_absences_non_justifiees,
                    $salarie->dernier_net_paye ? number_format($salarie->dernier_net_paye, 2, ',', ' ') : '',
                    $salarie->mois_dernier_paye,
                    $salarie->created_at->format('d/m/Y H:i:s'),
                    $salarie->updated_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'exportation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function nouveauConge()
    {
        $salaries = Salarie::orderBy('nom_complet')->get();
        return view('customer.components.rh.nouveau_conge', compact('salaries'));
    }
} 