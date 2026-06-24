<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\TechnicianProfile; // Hakikisha hii ipo
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Notifications\SendOtpNotification;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:15',
            'role'     => 'required|in:technician,customer',
            'category_id' => 'nullable|required_if:role,technician|exists:categories,id',
            'password' => 'required|min:8|confirmed',
            'certificate'      => 'required_if:role,technician|file|mimes:pdf,jpg,png|max:2048',
            'residency_letter' => 'required_if:role,technician|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $otp = rand(100000, 999999);

        // 1. Tengeza User
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'role'           => $request->role,
            'password'       => Hash::make($request->password),
            'is_verified'    => false,
            'terms_accepted' => false,
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'category_id'    => ($request->role === 'technician') ? $request->category_id : null,
        ]);

        // 2. Ikiwa ni Fundi, tengeneza profile kwenye technician_profiles table
        if ($request->role === 'technician') {
            $certPath = $request->file('certificate')->store('technicians/certificates', 'public');
            $letterPath = $request->file('residency_letter')->store('technicians/letters', 'public');

            $user->technicianProfile()->create([
                'category_id'           => $request->category_id, // Imeongezwa ili kuzuia error ya 1364
                'certificate_path'      => $certPath,
                'residency_letter_path' => $letterPath,
                'status'                => 'pending',
            ]);
        }

        $user->notify(new SendOtpNotification($otp));

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('otp.verify.form');
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function resendOtp(Request $request)
{
    $user = $request->user(); // Au find user kwa kutumia email/id

    // Generates mpya
    $otp = rand(100000, 999999);
    $user->otp_code = $otp;
    $user->otp_expires_at = now()->addMinutes(10);
    $user->save();

    // Hapa ndipo MailHog itapokea email hii
    $user->notify(new \App\Notifications\SendOtpNotification($otp));

    return back()->with('success', 'OTP has been sent to your email.');
}
}