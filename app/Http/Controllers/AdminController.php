<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Activity;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Message;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get customers with pagination
        $customers = Customer::orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $totalUsers = Customer::count();
        $activeUsers = Customer::where('status', 'active')->count();
        $inactiveUsers = Customer::where('status', 'inactive')->count();
        $newUsers = Customer::where('created_at', '>=', now()->subDays(30))->count();
        
        // Get recent activities
        $recentActivities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get messages and unread count
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        $unreadMessages = ContactMessage::where('read', false)->count();
        
        return view('admin.dashboard', compact(
            'customers',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'newUsers',
            'unreadMessages',
            'recentActivities',
            'messages'
        ));
    }

    public function stats()
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);
        $sixtyDaysAgo = $now->copy()->subDays(60);

        // Get monthly data for user growth chart
        $monthlyGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyGrowth[] = [
                'month' => $month->format('M'),
                'count' => Customer::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count()
            ];
        }

        // Current period stats
        $totalUsers = Customer::count();
        $activeUsers = Customer::where('status', 'active')->count();
        $inactiveUsers = Customer::where('status', 'inactive')->count();
        $newUsers = Customer::where('created_at', '>=', $thirtyDaysAgo)->count();

        // Previous period stats for comparison
        $previousTotalUsers = Customer::where('created_at', '<', $thirtyDaysAgo)->count();
        $previousActiveUsers = Customer::where('status', 'active')
            ->where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();

        // Calculate growth percentages
        $totalGrowth = $previousTotalUsers > 0 ? (($totalUsers - $previousTotalUsers) / $previousTotalUsers) * 100 : 100;
        $activeGrowth = $previousActiveUsers > 0 ? (($activeUsers - $previousActiveUsers) / $previousActiveUsers) * 100 : 100;

        return response()->json([
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'newUsers' => $newUsers,
            'totalGrowth' => $totalGrowth,
            'activeGrowth' => $activeGrowth,
            'monthlyGrowth' => $monthlyGrowth,
            'userStatus' => [
                'active' => $activeUsers,
                'inactive' => $inactiveUsers
            ]
        ]);
    }

    /**
     * Log an activity
     */
    public function logActivity($userId, $type, $description)
    {
        Activity::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
        ]);
    }

    public function activities()
    {
        $activities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.activities', compact('activities'));
    }

    public function messages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        $unreadMessages = ContactMessage::where('read', false)->count();
        
        return view('admin.messages', compact('messages', 'unreadMessages'));
    }

    public function markMessageAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.voir_users', compact('customer'))->render();
    }

    public function toggleStatus($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
            
            $customer->status = $newStatus;
            $customer->save();
            
            // Log the activity
            $this->logActivity(
                auth()->id(),
                'update',
                'A ' . ($newStatus === 'active' ? 'activé' : 'désactivé') . ' le compte de ' . $customer->nom
            );
            
            return response()->json([
                'success' => true,
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du statut'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customerName = $customer->nom; // Store name before deletion for activity log
            
            // Delete the customer
            $customer->delete();
            
            // Log the activity
            $this->logActivity(
                auth()->id(),
                'delete',
                'A supprimé l\'utilisateur ' . $customerName
            );
            
            return response()->json([
                'success' => true,
                'message' => 'L\'utilisateur a été supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de l\'utilisateur'
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'current_password' => 'required_with:new_password',
                'new_password' => 'nullable|min:8|confirmed',
            ]);

            // Verify current password if trying to change password
            if ($request->new_password) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le mot de passe actuel est incorrect'
                    ], 400);
                }
                $data['password'] = Hash::make($request->new_password);
            }

            // Update user
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? $user->password,
            ]);

            // Log the activity
            $this->logActivity(
                $user->id,
                'update',
                'A mis à jour son profil'
            );

            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du profil'
            ], 500);
        }
    }

    public function filterUsers(Request $request)
    {
        $query = Customer::query();

        // Apply status filter only if a specific status is selected
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prenom', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('telephone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('entreprise', 'LIKE', "%{$searchTerm}%");
            });
        }

        $customers = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.partials.users-table', compact('customers'))->render();
        }

        return view('admin.dashboard', compact('customers'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'userIds' => 'required|array',
            'userIds.*' => 'exists:customers,id'
        ]);

        try {
            $userIds = $request->userIds;
            $action = $request->action;
            $count = count($userIds);

            switch ($action) {
                case 'activate':
                    Customer::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = $count . ' utilisateur(s) activé(s) avec succès';
                    break;

                case 'deactivate':
                    Customer::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    $message = $count . ' utilisateur(s) désactivé(s) avec succès';
                    break;

                case 'delete':
                    // Store names for activity log
                    $customerNames = Customer::whereIn('id', $userIds)->pluck('nom')->toArray();
                    
                    // Delete the customers
                    Customer::whereIn('id', $userIds)->delete();
                    
                    // Log the activity for each deleted customer
                    foreach ($customerNames as $name) {
                        $this->logActivity(
                            auth()->id(),
                            'delete',
                            'A supprimé l\'utilisateur ' . $name
                        );
                    }
                    
                    $message = $count . ' utilisateur(s) supprimé(s) avec succès';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }
} 