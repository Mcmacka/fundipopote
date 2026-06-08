<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     * Stores choice in session — persists across pages.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Only allow supported locales
        $supported = ['en', 'sw'];

        if (! in_array($locale, $supported)) {
            abort(400, 'Unsupported locale.');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store',
        ]);
    }
}
