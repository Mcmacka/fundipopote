<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CustomerPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // Show payment page for a completed booking
    public function create(Booking $booking): View
    {
        abort_if($booking->customer_id !== auth()->id(), 403);
        abort_if($booking->status !== 'accepted', 403, 'Malipo yanaweza kufanywa tu kwa ombi lililokubaliwa.');

        return view('customer.payment.create', compact('booking'));
    }

    // Store payment reference
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->customer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'payment_method'  => 'required|in:mpesa,tigopesa,airtel,cash',
            'mpesa_reference' => 'required_unless:payment_method,cash|nullable|string|max:50',
            'amount_paid'     => 'required|numeric|min:1',
        ]);

        // Update booking with payment info
        $booking->update([
            'status'           => 'in_progress',
            'agreed_price'     => $validated['amount_paid'],
        ]);

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with('success', 'payment has been checked thank you for using fundipopote.');
    }
}
