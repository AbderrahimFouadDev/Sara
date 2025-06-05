<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use LogsActivity;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('customer.dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'telephone' => 'required|string|max:20',
            'entreprise' => 'nullable|string|max:255',
            'adresse_entreprise' => 'nullable|string|max:255',
            'secteur' => 'nullable|string|max:100',
        ]);

        $customer = Customer::create($validated);

        // Log the activity
        $this->logActivity(
            'new_user',
            "Nouveau client créé: {$customer->nom} {$customer->prenom}"
        );

        return response()->json($customer, 201);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'telephone' => 'required|string|max:20',
            'entreprise' => 'nullable|string|max:255',
            'adresse_entreprise' => 'nullable|string|max:255',
            'secteur' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $oldStatus = $customer->status;
        $customer->update($validated);

        // Log status change if it occurred
        if ($oldStatus !== $validated['status']) {
            $this->logActivity(
                'status_change',
                "Statut du client modifié: {$customer->nom} {$customer->prenom} ({$oldStatus} → {$validated['status']})"
            );
        }

        // Log general update
        $this->logActivity(
            'profile_update',
            "Profil client mis à jour: {$customer->nom} {$customer->prenom}"
        );

        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        $customerName = "{$customer->nom} {$customer->prenom}";
        $customer->delete();

        // Log the deletion
        $this->logActivity(
            'user_deleted',
            "Client supprimé: {$customerName}"
        );

        return response()->json(['message' => 'Client supprimé avec succès']);
    }

    public function loadComponent($view, Request $request)
    {
        // Add profil_customer to the viewMap
        $viewMap = [
            'dashboard' => 'customer.dashboard',
            'profil_customer' => 'customer.components.profil_customer',
            'edit_profil_customer' => 'customer.components.edit_profil_customer',
            'parametres' => 'customer.components.parametres',
            // ... other mappings ...
        ];

        // Handle client-related components
        if (str_starts_with($view, 'clients/')) {
            return app(ClientController::class)->loadComponent($view, $request);
        }

        // Get the actual view path from the map, or use the original view name
        $viewPath = $viewMap[$view] ?? 'customer.components.' . $view;

        // Check if the view exists
        if (!view()->exists($viewPath)) {
            return response()->json([
                'message' => "View [{$viewPath}] not found.",
                'status' => 'error'
            ], 404);
        }

        return view($viewPath);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('email', $user->email)->first();
            
            if (!$customer) {
                throw new \Exception('Client non trouvé');
            }

            // Validate the request
            $validated = $request->validate([
                'nom' => 'nullable|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customer->id,
                'telephone' => 'nullable|string|max:20',
                'entreprise' => 'nullable|string|max:255',
                'adresse_entreprise' => 'nullable|string|max:255',
                'secteur' => 'nullable|string|max:100',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Start a database transaction
            \DB::beginTransaction();

            try {
                // Handle photo upload if present
                if ($request->hasFile('photo')) {
                    // Delete old photo if exists
                    if ($customer->photo && \Storage::disk('public')->exists($customer->photo)) {
                        \Storage::disk('public')->delete($customer->photo);
                    }
                    
                    // Store the new photo with a unique name
                    $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
                    $photoPath = $request->file('photo')->storeAs('customer-photos', $photoName, 'public');
                    $customer->photo = $photoPath;
                }

                // Update other fields if they are present
                if ($request->filled('nom')) {
                    $customer->nom = $validated['nom'];
                    $user->name = $validated['nom'] . ' ' . ($validated['prenom'] ?? $customer->prenom);
                }
                if ($request->filled('prenom')) {
                    $customer->prenom = $validated['prenom'];
                    $user->name = ($validated['nom'] ?? $customer->nom) . ' ' . $validated['prenom'];
                }
                if ($request->filled('email')) {
                    $customer->email = $validated['email'];
                    $user->email = $validated['email'];
                }
                if ($request->filled('telephone')) {
                    $customer->telephone = $validated['telephone'];
                }
                if ($request->filled('entreprise')) {
                    $customer->entreprise = $validated['entreprise'];
                }
                if ($request->filled('adresse_entreprise')) {
                    $customer->adresse_entreprise = $validated['adresse_entreprise'];
                }
                if ($request->filled('secteur')) {
                    $customer->secteur = $validated['secteur'];
                }

                $customer->save();
                $user->save();

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Profil mis à jour avec succès',
                    'photo_url' => $customer->photo ? asset('storage/' . $customer->photo) : null
                ]);
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }

    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Client non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'invoice_notifications' => 'boolean',
            'marketing_notifications' => 'boolean',
        ]);

        $customer->notification_preferences = $validated;
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Préférences de notification mises à jour'
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Client non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'language' => 'required|in:fr,en,ar',
            'theme' => 'required|in:light,dark,system',
        ]);

        $customer->display_preferences = $validated;
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Préférences d\'affichage mises à jour'
        ]);
    }

    public function deleteAccount()
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('email', $user->email)->first();

            if (!$customer) {
                throw new \Exception('Client non trouvé');
            }

            \DB::beginTransaction();

            try {
                // Delete customer record
                $customer->delete();
                
                // Delete user record
                $user->delete();

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Compte supprimé avec succès'
                ]);
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du compte: ' . $e->getMessage()
            ], 500);
        }
    }
}
