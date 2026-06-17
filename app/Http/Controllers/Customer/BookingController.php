<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TechnicianProfile;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str; 

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings made by the authenticated customer.
     */
    public function index(): View
    {
        $bookings = Booking::where('customer_id', auth()->id())
            ->with(['technician.technicianProfile', 'category'])
            ->latest() 
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request): View
    {
        $technicianId = $request->query('technician_id');
        
        if (!$technicianId) {
            abort(404, 'Technician not identified to initiate the booking process.');
        }

        $profile = TechnicianProfile::where('user_id', $technicianId)
            ->with(['user', 'category'])
            ->firstOrFail();

        return view('customer.bookings.create', compact('profile'));
    }

    /**
     * Store a newly created booking in storage.
     */
    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'technician_id'    => ['required', 'exists:users,id'],
            'category_id'      => ['required', 'exists:categories,id'],
            'description'      => ['required', 'string', 'min:10', 'max:1000'],
            'location_address' => ['required', 'string', 'max:255'],
            'location_lat'     => ['required', 'numeric'],
            'location_lng'     => ['required', 'numeric'],
            'scheduled_at'     => ['nullable', 'date'],
        ]);

        $validated['booking_code'] = 'BK-' . strtoupper(Str::random(6));
        $validated['customer_id'] = auth()->id(); 
        $validated['status'] = 'pending';         

        $booking = Booking::create($validated);

        // Notification: Tunamtumia fundi taarifa ya booking mpya
        $technician = \App\Models\User::find($validated['technician_id']);
        
        if ($technician) {
            $technician->notify(new \App\Notifications\BookingRequestNotification($booking));
        }

        \App\Jobs\CancelPendingBooking::dispatch($booking->id)->delay(now()->addMinutes(5));

        return redirect()
            ->route('customer.bookings.show', $booking->id)
            ->with('success', 'Your service request has been sent successfully.');
    }
  

    /**
     * Display the specified booking summary.
     */
    public function show(Booking $booking): View
    {
        // Kuhakikisha mteja anaona bookings zake tu
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['technician.technicianProfile', 'category']);

        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Store a new rating/review.
     */
    /**
     * Store a new rating/review.
     */
    public function rate(Request $request, Booking $booking): RedirectResponse
    {
        // 1. Uhakiki wa usalama
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        // 2. Uhakiki wa hali ya booking
        if ($booking->status !== 'completed' || $booking->review()->exists()) {
            return redirect()->back()->with('error', 'Invalid action or you have already rated this service.');
        }

        // 3. Validating data
        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // 4. Ku-create review
        Review::create([
            'booking_id'    => $booking->id,
            'customer_id'   => auth()->id(),
            'technician_id' => $booking->technician_id,
            'rating'        => $validated['rating'],
            'comment'       => $validated['comment'],
        ]);

        // --- HAPA NDIPO TUNATUMA NOTIFICATION YA SHUKRANI ---
        auth()->user()->notify(new \App\Notifications\ThankYouNotification(
            "Thank you for your feedback! We appreciate you taking the time to share your experience with us. Your review helps us improve our services and assists other customers in making informed decisions. We look forward to serving you again in the future!"
        ));
        // ----------------------------------------------------

        return redirect()->route('customer.bookings.index')
                         ->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }

    public function edit(Booking $booking)
{
    // Mteja anaweza ku-edit ikiwa tu status ni 'pending'
    abort_if($booking->status !== 'pending', 403, 'You cannot edit this request anymore.');
    
    return view('customer.bookings.edit', compact('booking'));
}

public function update(Request $request, Booking $booking)
{
    abort_if($booking->status !== 'pending', 403);

    $validated = $request->validate([
        'description' => 'required|string|max:500',
        'location_address' => 'required|string|max:255',
    ]);

    $booking->update($validated);
    return redirect()->route('customer.bookings.index')->with('success', 'Request updated successfully!');
}


public function destroy(Booking $booking)
{
    // Uhakiki wa usalama (mteja aone zake tu)
    if ($booking->customer_id !== auth()->id()) {
        abort(403, 'Unauthorized.');
    }

    // Ruhusu kufuta ikiwa status ni pending, cancelled, AU completed
    $allowedStatuses = ['pending', 'cancelled', 'completed'];
    
    if (!in_array($booking->status, $allowedStatuses)) {
        return redirect()->back()->with('error', 'you cannot delete this booking.');
    }

    $booking->delete();
    
    return redirect()->route('customer.bookings.index')
                     ->with('success', 'Booking request removed successfully.');
}

public function acceptPrice(Request $request, Booking $booking): RedirectResponse
{
    // 1. Uhakiki: Mteja anaruhusiwa kubali kama status ni 'waiting_for_customer'
    if ($booking->status !== 'waiting_for_customer') {
        return redirect()->back()->with('error', 'You cannot accept the price at this time.');
    }

    // 2. Uhakiki: Hakikisha kuna bei iliyowekwa
    if ($booking->agreed_price <= 0) {
        return redirect()->back()->with('error', 'The agreed price is not valid, please contact the technician.');
    }

    // 3. Kubadilisha status
    $booking->update(['status' => 'accepted']);

    // --- NOTIFICATION KWA FUNDI ---
    $technician = \App\Models\User::find($booking->technician_id);
    if ($technician) {
        // Hapa unatumia ile Notification Class tuliyotaja awali (PriceAcceptedNotification)
        $technician->notify(new \App\Notifications\PriceAcceptedNotification($booking));
    }
    // ------------------------------

    return redirect()->back()->with('success', 'The price has been accepted, the work will start soon.');
}
    
}