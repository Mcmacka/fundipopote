<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function accept(Request $request)
{
    $request->validate(['accept' => 'required|accepted']);
    
    auth()->user()->update(['terms_accepted' => true]);
    
    return redirect()->intended('dashboard');
}
}
