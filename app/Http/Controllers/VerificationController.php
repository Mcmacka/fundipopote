<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // --- OTP VERIFICATION ---
    public function showOtpForm() 
    { 
        // Tunapata email ya mtumiaji aliyelogin
        $email = auth()->user()->email;
        
        // Tunapitisha email hiyo kwenye view (Hakikisha file lako linaitwa verify-otp.blade.php)
        return view('auth.otp', ['email' => $email]);
    }

    public function verifyOtp(Request $request)
    {
        // Validation ya OTP 6 (kama ulivyoelekeza kwenye form)
        $request->validate(['otp' => 'required|numeric|digits:6']);
        
        $user = auth()->user();

        if ($user && $request->otp == $user->otp_code && now()->lessThan($user->otp_expires_at)) {
            $user->update(['is_verified' => true, 'otp_code' => null]);
            return redirect()->route('terms.show');
        }

        return back()->withErrors(['otp' => 'Invalid OTP or expired.']);
    }

    // --- TERMS & CONDITIONS ---
    public function showTerms() { return view('auth.terms'); }

    public function acceptTerms(Request $request)
{
    // Hii bado itafanya kazi kwa sababu tunatuma 'accept' => 1
    $request->validate([
        'accept' => 'required|accepted'
    ]);
    
    auth()->user()->update(['terms_accepted' => true]);
    return redirect()->route('dashboard');
}
}