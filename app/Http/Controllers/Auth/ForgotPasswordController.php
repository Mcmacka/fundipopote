<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User; // Hakikisha ume-import Model ya User

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // 1. Tafuta mtumiaji kwa email yake
        $user = User::where('email', $request->email)->first();

        // 2. Zuia kama mtumiaji huyo ni Admin (Rekebisha 'isAdmin()' kulingana na njia unayotumia kutambua admin)
        if ($user && $user->isAdmin()) {
            return back()->withErrors(['email' => 'Password reset is not allowed for administrator accounts.']);
        }

        // 3. Endelea na logic ya kawaida ya Laravel
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}