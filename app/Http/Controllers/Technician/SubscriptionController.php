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
        $validated = $request->validate([
            'plan_type'       => 'required|in:basic,standard,premium',
            'mpesa_reference' => 'required|string|max:50',
            'payment_method'  => 'required|in:mpesa,tigopesa,airtel',
        ]);

        $amount = Subscription::$planPrices[$validated['plan_type']];

        Subscription::create([
            'user_id'         => auth()->id(),
            'plan_type'       => $validated['plan_type'],
            'amount_paid'     => $amount,
            'mpesa_reference' => $validated['mpesa_reference'],
            'payment_method'  => $validated['payment_method'],
            'status'          => 'pending_approval',
        ]);

        return redirect()
            ->route('technician.subscription.index')
            ->with(
                'success',
                'Your payment has been received! The admin will review it shortly.'
            );
    }
}
