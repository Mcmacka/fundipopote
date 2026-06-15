<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * IsAdminMiddleware
 *
 * Guards the Filament admin panel. Only users with role='admin' may pass.
 * Registered as alias 'is.admin' in bootstrap/app.php
 */
class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            abort(403, 'Don\'t have permission to access this resource.');
        }

        return $next($request);
    }
}
