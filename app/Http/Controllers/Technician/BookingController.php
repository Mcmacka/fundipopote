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
    // Tafuta Booking kwa kutumia ID, ikikosekana itarudisha 404 (badala ya null error)
    $booking = Booking::findOrFail($id);

    // Lazimisha Laravel ichote upya data ya booking kutoka database ili kupata update ya sasa
    $booking = $booking->fresh();

    // Sasa angalia ruhusa na data mpya
    $isOwner = $booking->technician_id == auth()->id();
    $isAccepted = in_array($booking->status, ['accepted', 'in_progress', 'completed']);

    if (!$isOwner || !$isAccepted) {
        // Hapa tunajua sasa kama ni Owner NO au Status ndiyo tatizo
        abort(403, "Access Denied. Owner: " . ($isOwner ? 'Yes' : 'No') . ", Status: " . $booking->status);
    }

    $booking->load('customer');
    return view('technician.bookings.show', compact('booking'));
}

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        // Ruhusu 'accept' hata kama technician_id bado ni null
        if ($request->status !== 'accepted') {
            abort_if($booking->technician_id != auth()->id(), 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,completed',
            'reason' => 'nullable|string|max:255'
        ]);

        switch ($request->status) {
            case 'accepted':
                // Imepitishwa vigezo 2 pekee (booking na user) ili kuendana na BookingService
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

    public function complete(Booking $booking): RedirectResponse
    {
        abort_if($booking->technician_id != auth()->id(), 403);

        $this->bookingService->completeBooking($booking, auth()->user());
        event(new \App\Events\BookingCompleted($booking));

        return redirect()->back()->with('success', 'Job is completed successfully!');
    }
}