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

    public function index(): View
    {
        $bookings = auth()->user()
            ->bookingsAsTechnician()
            ->with(['customer', 'category'])
            ->latest()
            ->paginate(10);

        return view('technician.bookings.index', compact('bookings'));
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

    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $this->bookingService->rejectBooking(
            $booking,
            $validated['reason'] ?? '',
            auth()->user()
        );

        return back()->with('info', 'You have rejected this booking.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->bookingService->cancelBooking($booking, auth()->user());

        return back()->with('info', 'You have cancelled this booking.');
    }

  public function complete(Booking $booking): RedirectResponse
{
    $this->bookingService->completeBooking($booking, auth()->user());

    // Hii itapeleka taarifa kwa mteja anayehusika
    event(new \App\Events\BookingCompleted($booking));

    return redirect()->route('technician.bookings.index')
                     ->with('success', 'Kazi imekamilika!');
}
}