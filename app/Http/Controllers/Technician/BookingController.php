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
        $pendingBookings = auth()->user()
            ->bookingsAsTechnician()
            ->where('status', 'pending')
            ->latest()
            ->get();

        $bookings = auth()->user()
            ->bookingsAsTechnician()
            ->whereIn('status', ['accepted', 'in_progress', 'completed', 'waiting_for_customer'])
            ->with(['customer', 'category'])
            ->latest()
            ->paginate(10);

        return view('technician.bookings.index', compact('pendingBookings', 'bookings'));
    }

    public function show($id): View
    {
        $booking = Booking::findOrFail($id);
        
        // Kuzuia mtu asiyehusika
        abort_if($booking->technician_id != auth()->id(), 403);

        // Ruhusu kuona kazi kama ni 'pending' (kwa ajili ya bei) AU 'accepted' nk.
        $booking->load('customer');
        return view('technician.bookings.show', compact('booking'));
    }

    public function proposePrice(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate(['agreed_price' => 'required|numeric']);
        
        $booking->update([
            'agreed_price' => $request->agreed_price,
            'status' => 'waiting_for_customer'
        ]);

        return redirect()->back()->with('success', 'price has been proposed.');
    }

   public function update(Request $request, Booking $booking): RedirectResponse
{
    // 1. Ongeza 'cancelled' kwenye validation
    $request->validate([
        'status' => 'required|in:accepted,rejected,completed,cancelled',
        'reason' => 'nullable|string|max:255'
    ]);

    abort_if($booking->technician_id != auth()->id(), 403);

    $message = 'Booking status updated successfully.';

    switch ($request->status) {
        case 'accepted':
            $this->bookingService->acceptBooking($booking, auth()->user());
            $message = 'You have accepted the job.';
            break;
        
        case 'rejected':
            $booking->update(['status' => 'rejected']);
            $message = 'You have rejected the job.';
            break;

        case 'completed':
            $booking->update(['status' => 'completed', 'completed_at' => now()]);
            $message = 'You have marked the job as completed.';
            break;

        case 'cancelled':
            $booking->update(['status' => 'cancelled']);
            // Notification inatumwa hapa
            $booking->customer->notify(new \App\Notifications\BookingStatusNotification($booking));
            $message = 'You have cancelled the job.';
            break;
    }

    return redirect()->back()->with('success', $message);
}

    public function handle()
{
    // Tafuta bookings ambazo ni 'pending' na zimezidi dakika 5
    $delayedBookings = Booking::where('status', 'pending')
        ->where('created_at', '<', now()->subMinutes(5))
        ->get();

    foreach ($delayedBookings as $booking) {
        $booking->update(['status' => 'cancelled']);
        
        // Tuma notification kwa mteja
        $booking->customer->notify(new \App\Notifications\BookingStatusNotification($booking));
    }

    $this->info('Bookings that have been pending for more than 5 minutes have been cancelled.');
}

public function updateNotes(Request $request, Booking $booking): RedirectResponse
{
    // Hakikisha ni fundi husika pekee anayeweza kubadilisha
    abort_if($booking->technician_id != auth()->id(), 403);

    // Validate maelezo
    $request->validate([
        'technician_notes' => 'nullable|string|max:1000'
    ]);

    // Sasisha maelezo pekee
    $booking->update([
        'technician_notes' => $request->technician_notes
    ]);

    return redirect()->back()->with('success', 'Notes updated successfully.');
}

}