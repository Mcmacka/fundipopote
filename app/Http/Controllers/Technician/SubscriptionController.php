<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $currentSubscription = $user->activeSubscription;
        $allSubscriptions    = $user->subscriptions()->latest()->get();
        $plans               = Subscription::$planPrices;

        return view('technician.subscription.index', compact(
            'currentSubscription',
            'allSubscriptions',
            'plans'
        ));
    }

public function store(Request $request): RedirectResponse
{
    $user = auth()->user();

    // 1. Validation
    $validated = $request->validate([
        'plan_type'       => 'required|in:basic,standard,premium',
        'payment_method'  => 'required',
        'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 2. Kuzuia malipo mara mbili (Kama tayari ana ombi la pending au queued)
    $hasExistingRequest = \App\Models\Subscription::where('user_id', $user->id)
        ->whereIn('status', ['pending_approval', 'queued'])
        ->exists();

    if ($hasExistingRequest) {
        return back()->withErrors(['error' => 'you already have a pending subscription request.']);
    }

    // 3. Weka bei
    $prices = Subscription::$planPrices;
    $amount = $prices[$validated['plan_type']];

    // 4. Hifadhi faili
    $path = $request->file('payment_receipt')->store('subscriptions/receipts', 'public');

    // 5. Logic ya "Active" vs "Queued"
    // Kama ana subscription inayofanya kazi (active), hii mpya iwe 'queued'
    // Vinginevyo, iwe 'pending_approval'
    $status = $user->activeSubscription ? 'queued' : 'pending_approval';

    // 6. Hifadhi kwenye database
    \App\Models\Subscription::create([
        'user_id'         => $user->id,
        'plan_type'       => $validated['plan_type'],
        'amount_paid'     => $amount,
        'payment_receipt' => $path,
        'payment_method'  => $validated['payment_method'],
        'status'          => $status,
    ]);

    $message = ($status === 'queued') 
        ? 'your request has been submitted and is queued for approval.' 
        : 'your request has been submitted and is pending approval.';

    return redirect()->route('technician.subscription.index')->with('success', $message);
}
}
