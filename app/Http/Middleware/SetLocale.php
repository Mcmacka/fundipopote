<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale Middleware
 *
 * Reads the user's language preference from the session
 * and applies it to every request automatically.
 *
 * Registered in bootstrap/app.php as web middleware.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')){
            App::setLocale('en');
            return $next($request);
        }
        $locale = Session::get('locale', config('app.locale', 'en'));

        // Safety check — only allow supported locales
        if (! in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
