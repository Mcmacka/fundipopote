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
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'email or password is not correct check again.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // LOGIC YA REDIRECT KWA FUNDI
        if ($user->role === 'technician') {
            // Kama ana subscription inayofanya kazi, mpeleke Dashboard
            if ($user->hasActiveSubscription()) {
                return redirect()->route('technician.dashboard');
            }
            // Kama hana, mpeleke kwenye page ya malipo
            return redirect()->route('technician.subscription.index');
        }

        // REDIRECT KWA ROLES NYINGINE
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