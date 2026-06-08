<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    /**
     * Orodha ya kazi zote za fundi
     */
    public function index(): View
    {
        $bookings = auth()->user()
            ->bookingsAsTechnician()
            ->with(['customer', 'category'])
            ->latest()
            ->paginate(10);

        return view('technician.bookings.index', compact('bookings'));
    }

    /**
     * Kuona taarifa kamili za ombi (Request Details)
     */
    public function show(Booking $booking): View
    {
        // Kuhakikisha ni fundi husika tu anayeweza kuona kazi hii
        abort_if($booking->technician_id !== auth()->id(), 403);

        // Load data ya mteja ili ionekane kwenye view
        $booking->load('customer');

        return view('technician.bookings.show', compact('booking'));
    }

    /**
     * Kusimamia mabadiliko ya status (Accept, Reject, Complete)
     */
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        // Ulinzi wa usalama
        abort_if($booking->technician_id !== auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:accepted,rejected,completed',
            'agreed_price' => 'required_if:status,accepted|numeric|min:0',
            'reason' => 'nullable|string|max:255'
        ]);

        switch ($request->status) {
            case 'accepted':
                $this->bookingService->acceptBooking($booking, $request->agreed_price, auth()->user());
                $message = 'You have accepted this job.';
                break;

            case 'rejected':
                $this->bookingService->rejectBooking($booking, $request->reason ?? '', auth()->user());
                $message = 'You have rejected this job.';
                break;

            case 'completed':
                $this->bookingService->completeBooking($booking, auth()->user());
                // Event ya notification kwa mteja
                event(new \App\Events\BookingCompleted($booking));
                $message = 'Job marked as completed successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function accept(Request $request, Booking $booking): RedirectResponse
{
    $validated = $request->validate([
        'agreed_price' => 'required|numeric|min:0',
    ]);

    $this->bookingService->acceptBooking(
        $booking,
        $validated['agreed_price'],
        auth()->user()
    );

    return back()->with('success', 'You have accepted this booking.');
}

public function complete(Booking $booking): RedirectResponse
{
    // Hakikisha ni fundi husika
    abort_if($booking->technician_id !== auth()->id(), 403);

    // Tumia service kama ulivyopanga
    $this->bookingService->completeBooking($booking, auth()->user());

    // Event ya notification
    event(new \App\Events\BookingCompleted($booking));

    return redirect()->back()->with('success', 'Job is completed successfully!');
}


}