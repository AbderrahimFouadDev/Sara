<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;              // ← add this



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function step1(Request $request)
{
    $validated = $request->validate([
        'nom'      => 'required|string|max:255',
        'prenom'   => 'required|string|max:255',
        'telephone'=> 'required|string|max:20',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    Session::put('register.step1', $validated);
    return response()->json(['next' => 2]);
}

public function step2(Request $request)
{
    $request->validate([
        'code_verification' => 'required|string|min:4|max:6'
    ]);

    // You can verify the code here
    Session::put('register.step2', $request->only('code_verification'));
    return response()->json(['next' => 3]);
}

public function step3(Request $request)
{
    Session::put('register.step3', $request->only('entreprise', 'adresse_entreprise', 'secteur'));
    return response()->json(['next' => 4]);
}
public function finish(Request $request)
{
    // Only pull step1 (where you stored nom, prenom, email, password)
    $step1 = Session::pull('register.step1');
    $step3 = Session::pull('register.step3'); 

    // Create the user — concatenate nom + prenom into the 'name' column
    $user = User::create([
        'name'     => $step1['nom'] . ' ' . $step1['prenom'],
        'email'    => $step1['email'],
        'password' => Hash::make($step1['password']),
    ]);

    Customer::create([
        'nom'                 => $step1['nom'],
        'prenom'              => $step1['prenom'],
        'telephone'           => $step1['telephone'],
        'email'               => $step1['email'],
        'password'            => Hash::make($step1['password']),
        'entreprise'          => $step3['entreprise'] ?? null,
        'adresse_entreprise'  => $step3['adresse_entreprise'] ?? null,
        'secteur'             => $step3['secteur'] ?? null,
    ]);

    // Fire Registered event, log in and redirect
    event(new Registered($user));
    Auth::login($user);

    if ($user->type === 'ADM') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.dashboard');
}
}
