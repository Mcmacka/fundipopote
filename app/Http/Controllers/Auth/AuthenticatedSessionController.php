<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController
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

        // TUMEBORESHA HAPA: Tumetumia to() na route() badala ya intended()
        return match ($user->role) {
            'admin'      => redirect()->to('/admin'),
            'technician' => redirect()->to(route('technician.subscription.index')),
            default      => redirect()->to(route('customer.search')),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        // Hizi mbili zinasafisha kila kitu kilichohifadhiwa kwenye session ya kivinjari
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // TUMEBORESHA HAPA: Inamlazimisha kwenda kwenye route yenye jina 'welcome'
        return redirect()->route('welcome');
    }
}