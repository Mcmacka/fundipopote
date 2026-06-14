<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

   public function store(Request $request): RedirectResponse
{
    // 1. Validating input ya 'login' (email au phone)
    $request->validate([
        'login'    => 'required|string',
        'password' => 'required',
    ]);

    // 2. Kuamua kama ni email au phone
    $loginValue = $request->input('login');
    
    // Angalia kama input ina '@' (kama ndio, tunachukulia ni email)
    $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    // 3. Jaribu kulogin (Auth::attempt inahitaji array ya credentials)
    if (! Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->boolean('remember'))) {
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Logic ya redirect kwa role husika
    if ($user->role === 'technician') {
        return $user->hasActiveSubscription() 
            ? redirect()->route('technician.dashboard') 
            : redirect()->route('technician.subscription.index');
    }

    return match ($user->role) {
        'admin' => redirect()->to('/admin'),
        default => redirect()->route('customer.search'),
    };
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}