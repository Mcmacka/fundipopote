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

        // 5. Baada ya ku-rate, mpeleke mteja kwenye index ili aone ma-booking yake mengine 
        // na ujumbe wa shukrani (Success Message).
        return redirect()->route('customer.bookings.index')
                         ->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }

    
}