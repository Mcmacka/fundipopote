<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle($request, Closure $next)
{
    // Kama mtumiaji haja-login, mruhusu aende kwenye login page
    if (!auth()->check()) {
        return $next($request);
    }

    // Kama ni admin, mruhusu aingie
    if (auth()->user()->is_admin) {
        return $next($request);
    }

    // Kama si admin, mlogout na umrudishe kwenye welcome page
    auth()->logout();
    return redirect('/');
}
}

