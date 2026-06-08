@extends('layouts.app')

{{-- Tunasoma jina la fundi kupitia muundo wa $booking infrastructure --}}
@section('title', 'Booking Details - ' . $booking->technician->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    {{-- Navigation Pathway --}}
    <a href="{{ route('customer.bookings.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-emerald-600 transition mb-6 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to My Bookings
    </a>

    {{-- Alert ya mafanikio pindi mteja anapotoka kutengeneza booking mpya --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile & Booking Hero Container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8 text-white">
            <div class="flex items-center gap-4">
                {{-- Profile Photo Rendering Pipeline --}}
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-3xl font-bold border-2 border-white/30 shadow-lg overflow-hidden flex-shrink-0">
                    @if($booking->technician->technicianProfile && $booking->technician->technicianProfile->profile_photo)
                        <img src="{{ asset('storage/' . $booking->technician->technicianProfile->profile_photo) }}" 
                             class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($booking->technician->name, 0, 2)) }}
                    @endif
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold leading-tight">{{ $booking->technician->name }}</h1>
                    <span class="inline-block mt-1 px-2.5 py-0.5 bg-white/20 border border-white/30 text-emerald-50 text-xs font-semibold rounded-full uppercase tracking-wider">
                        {{ $booking->category->name }}
                    </span>
                    
                    {{-- Star Rating Pipeline --}}
                    @if($booking->technician->technicianProfile)
                        <div class="flex items-center gap-1 mt-2.5">
                            @php 
                                $rating = $booking->technician->technicianProfile->average_rating ?? 0; 
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($rating) ? 'text-amber-400' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-white/90 text-xs font-medium ml-1">
                                {{ number_format($rating, 1) }} Rating
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Core Booking Meta Block --}}
        <div class="p-6 border-b border-gray-50">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Booking Reference</span>
                    <span class="text-sm font-mono font-bold text-gray-800 bg-white px-2.5 py-1 rounded-lg border border-gray-200">
                        {{ $booking->booking_code }}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Current Status</span>
                    @switch($booking->status)
                        @case('pending')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">Pending Approval</span>
                            @break
                        @case('accepted')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wide">In Progress</span>
                            @break
                        @case('completed')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Service Completed</span>
                            @break
                        @case('cancelled')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wide">Cancelled</span>
                            @break
                        @default
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-50 text-gray-700 border border-gray-200 uppercase tracking-wide">{{ $booking->status }}</span>
                    @endswitch
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2 tracking-tight">My Service Request Details</h3>
                    <p class="text-gray-600 bg-white p-4 rounded-xl border border-gray-200 leading-relaxed min-h-[100px]">
                        {{ $booking->description ?? 'No specific job details were recorded.' }}
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-1 tracking-tight">Service Location</h3>
                        <p class="text-gray-600 flex items-start gap-2 bg-white p-2.5 rounded-xl border border-gray-100">
                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $booking->location_address }}</span>
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-1 tracking-tight">Submitted On</h3>
                        <p class="text-gray-600 flex items-center gap-2 bg-white p-2.5 rounded-xl border border-gray-100">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </p>
                    </div>

                    @if($booking->agreed_price)
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1 tracking-tight">Agreed Service Price</h3>
                            <div class="bg-emerald-50/60 border border-emerald-100 px-4 py-2.5 rounded-xl">
                                <span class="text-emerald-700 font-extrabold text-lg">TSh {{ number_format($booking->agreed_price) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Technician Feedback Update Block --}}
        @if($booking->technician_notes)
            <div class="p-6 bg-slate-50/80 border-t border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Feedback / Update from Technician
                </h4>
                <p class="text-sm text-gray-600 italic bg-white p-4 rounded-xl border border-gray-100">
                    "{{ $booking->technician_notes }}"
                </p>
            </div>
        @endif

        {{-- Rating Section --}}
        @if($booking->status === 'completed' && auth()->id() === $booking->customer_id)
            <div class="p-6 border-t border-gray-100 bg-gray-800">
                @if(!$booking->review)
                    <h3 class="text-lg font-bold text-white mb-4">Rate This Service</h3>
                    <form action="{{ route('customer.bookings.rate', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Rating Score</label>
                            <select name="rating" required class="w-full rounded-xl border-gray-600 bg-gray-900 text-white p-3">
                                <option value="5">5 Stars - Excellent</option>
                                <option value="4">4 Stars - Very Good</option>
                                <option value="3">3 Stars - Average</option>
                                <option value="2">2 Stars - Poor</option>
                                <option value="1">1 Star - Bad</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-600 bg-gray-900 text-white p-3" placeholder="Leave a comment..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl">Submit Review</button>
                    </form>
                @else
                    <p class="text-emerald-400 font-bold">You have already rated this service.</p>
                @endif
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-3">
            @if($booking->technician->phone)
                <a href="tel:{{ $booking->technician->phone }}"
                   class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8V5z"/>
                    </svg>
                    Call {{ $booking->technician->name }}
                </a>
            @endif
        </div>

        @push('scripts')
<script type="module">
    // Inasikiliza channel ya mteja husika kwa kutumia Echo
    Echo.private('booking.{{ auth()->id() }}')
        .listen('BookingCompleted', (e) => {
            console.log("Kazi imekamilika! Tunarefresh ukurasa...");
            window.location.reload(); 
        });
</script>
@endpush

    </div>
</div>
@endsection

