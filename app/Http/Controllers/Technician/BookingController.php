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

    public function show($id): View
    {
        $booking = Booking::findOrFail($id);
        $booking = $booking->fresh();

        $isOwner = $booking->technician_id == auth()->id();
        $isAccepted = in_array($booking->status, ['accepted', 'in_progress', 'completed']);

        if (!$isOwner || !$isAccepted) {
            abort(403, "Access Denied. Owner: " . ($isOwner ? 'Yes' : 'No') . ", Status: " . $booking->status);
        }

        $booking->load('customer');
        return view('technician.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        if ($request->status !== 'accepted') {
            abort_if($booking->technician_id != auth()->id(), 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,completed',
            'reason' => 'nullable|string|max:255'
        ]);

        switch ($request->status) {
            case 'accepted':
                $this->bookingService->acceptBooking($booking, auth()->user());
                $message = 'You have accepted the job. Customer contact details are now visible.';
                break;

            case 'rejected':
                $this->bookingService->rejectBooking($booking, $request->reason ?? '', auth()->user());
                $message = 'You have rejected this job.';
                break;

            case 'completed':
                $this->bookingService->completeBooking($booking, auth()->user());
                event(new \App\Events\BookingCompleted($booking));
                $message = 'Job marked as completed successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function complete(Request $request, Booking $booking): RedirectResponse
{
    // Ongeza hii mstari ili kuona kama data inafika
    dd($request->all()); 

    abort_if($booking->technician_id != auth()->id(), 403);
    // ...
}

    
}