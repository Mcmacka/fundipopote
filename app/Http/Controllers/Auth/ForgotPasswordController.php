<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

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

        // We send the password reset link via Laravel's built-in broker
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // If the link was successfully sent, redirect back with success message
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // If it failed, redirect back with the error log
        return back()->withErrors(['email' => __($status)]);
    }
}