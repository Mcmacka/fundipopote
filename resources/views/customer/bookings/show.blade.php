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

    {{-- Alert ya mafanikio --}}
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
                        @case('waiting_for_customer')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wide">Waiting for Price Approval</span>
                            @break
                        @case('accepted')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wide">In Progress</span>
                            @break
                        @case('completed')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Service Completed</span>
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
                            <span class="font-medium text-gray-700">{{ $booking->location_address }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        
            {{-- Price Negotiation Section --}}
<div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm mt-6">
    <h3 class="font-bold text-gray-800 mb-4">Service Price History</h3>

    @if($booking->agreed_price > 0)
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
            <span class="text-gray-600 font-medium">Agreed Price:</span>
            <span class="text-xl font-extrabold text-emerald-600">
                {{ number_format($booking->agreed_price) }} TZS
            </span>
        </div>
    @else
        <p class="text-gray-400 italic">No price agreed for this service.</p>
    @endif

    {{-- Sehemu nyingine za ku-accept bei kama bado ni 'waiting_for_customer' --}}
    @if($booking->status === 'waiting_for_customer')
        <form action="{{ route('customer.bookings.accept-price', $booking->id) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold">
                Accept Price
            </button>
        </form>
    @endif
</div>

        {{-- Action Buttons --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-3">
            @if($booking->technician->phone)
                <a href="tel:{{ $booking->technician->phone }}"
                   class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition shadow-sm">
                    Call {{ $booking->technician->name }}
                </a>
            @endif
        </div>


        @if($booking->status === 'cancelled')
    <div style="background-color: #f8d7da; padding: 15px; border-radius: 8px; margin-top: 20px;">
        <h4 style="margin:0; color: #721c24;">Hali ya Ombi: Imeghairiwa</h4>
        <p style="margin: 5px 0 0;">
            @if(str_contains($booking->cancellation_reason, 'TIMEOUT'))
                Kazi imefutwa kiotomatiki kwa sababu fundi hakujibu ndani ya muda uliopangwa. Tafadhali jaribu kuomba fundi mwingine.
            @elseif(str_contains($booking->cancellation_reason, 'REJECTED'))
                Fundi amegairi kufanya kazi hii. Unaweza kutafuta fundi mwingine kwenye orodha yetu.
            @else
                Ombi hili limefutwa.
            @endif
        </p>
    </div>
@endif

        {{-- Review Section --}}
@if($booking->status === 'completed')
    <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm mt-6">
        <h3 class="font-bold text-gray-800 mb-4">Rate This Service</h3>
        
        @if($booking->review()->exists())
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl text-center">
                <p class="text-emerald-800 font-bold">✓ You have already submitted your feedback. Thank you!</p>
            </div>
        @else
            <form action="{{ route('customer.bookings.rate', $booking->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating (1-5 stars)</label>
                    <select name="rating" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Good</option>
                        <option value="3">3 - Average</option>
                        <option value="2">2 - Poor</option>
                        <option value="1">1 - Very Bad</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                    <textarea name="comment" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Share your experience..."></textarea>
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                    Submit Review
                </button>
            </form>
        @endif
    </div>
@endif
    </div>
</div>


@endsection