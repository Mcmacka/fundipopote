<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingStatusNotification;
use App\Jobs\CancelPendingBooking;

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
        
        abort_if($booking->technician_id != auth()->id(), 403);

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

        $message = "Fundi " . auth()->user()->name . " The price that proposed by Technician is " . number_format($request->agreed_price);
        $url = route('customer.bookings.show', $booking->id);
        
        $booking->customer->notify(new \App\Notifications\BookingNotification(
            $message, 
            $url, 
            $booking, 
            auth()->id()
        ));

        return redirect()->back()->with('success', 'Price proposed and customer notified.');
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
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
    $booking = $booking->fresh();
    
    try {
        if ($booking->customer) {
            // TUTAFUNGA HII KWENYE TRY CATCH
            $booking->customer->notify(new \App\Notifications\BookingStatusNotification($booking));
        }
    } catch (\Throwable $e) {
        \Log::error("NOTIFICATION FAILED: " . $e->getMessage());
        dd($e->getMessage()); // Hii itasimamisha kila kitu na kukuonyesha error kwenye screen yako
    }
    break;

        }
        return redirect()->back()->with('success', $message);
        
    }

    public function updateNotes(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->technician_id != auth()->id(), 403);

        $request->validate([
            'technician_notes' => 'nullable|string|max:1000'
        ]);

        $booking->update([
            'technician_notes' => $request->technician_notes
        ]);

        return redirect()->back()->with('success', 'Notes updated successfully.');
    }
}