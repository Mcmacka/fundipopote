<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowedRoutes = ['otp.verify.form', 'otp.verify', 'terms.show', 'terms.accept'];
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        if (!$user->is_verified) {
            return redirect()->route('otp.verify.form');
        }

        if (!$user->terms_accepted) {
            return redirect()->route('terms.show');
        }

        return $next($request);
    }
}