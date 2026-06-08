@extends('layouts.technician')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ auth()->user()->technicianProfile?->category?->name ?? 'Service' }} Technician</p>
        </div>
        @if($subscription)
            <span class="bg-emerald-50 text-emerald-700 text-sm px-4 py-1.5 rounded-full border border-emerald-200 font-medium">
                ✅ Active Subscription — expires {{ $subscription->expires_at->format('d M Y') }}
            </span>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-gray-900">{{ $pendingBookings->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">New Requests</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-gray-900">{{ $activeBookings->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">In Progress</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">{{ $completedCount }}</div>
            <div class="text-sm text-gray-500 mt-1">Completed</div>
        </div>
    </div>

    

    {{-- Pending Bookings --}}
    @if($pendingBookings->count() > 0)
    <h2 class="text-lg font-semibold text-gray-800 mb-3">New Requests</h2>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6">
        @foreach($pendingBookings as $booking)
            <div class="flex justify-between items-center py-4 border-b border-gray-50 last:border-0">
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->customer->name }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->category->name }} · {{ $booking->location_address }}</div>
                    <div class="text-xs text-gray-400 mt-0.5 font-mono">{{ $booking->booking_code }}</div>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($booking->description, 80) }}</p>
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <form method="POST" action="{{ route('technician.bookings.accept', $booking) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="agreed_price" value="0">
                        <button class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-xl transition w-full">
                             Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('technician.bookings.reject', $booking) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="reason" value="Unavailable">
                        <button class="px-4 py-1.5 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm rounded-xl transition w-full">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Active Bookings --}}
    @if($activeBookings->count() > 0)
    <h2 class="text-lg font-semibold text-gray-800 mb-3"> Tasks In Progress</h2>
    <div class="bg-white rounded-2xl border border-gray-100 p-4">
        @foreach($activeBookings as $booking)
            <div class="flex justify-between items-center py-4 border-b border-gray-50 last:border-0">
                <div>
                    <div class="font-medium text-gray-900">{{ $booking->customer->name }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->category->name }} · {{ $booking->location_address }}</div>
                    @if($booking->agreed_price)
                        <div class="text-sm font-medium text-emerald-600 mt-0.5">
                            TZS {{ number_format($booking->agreed_price) }}
                        </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('technician.bookings.complete', $booking) }}">
                    @csrf @method('PATCH')
                    <button class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-xl transition">
                         Complete
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    @endif

    @if($pendingBookings->count() === 0 && $activeBookings->count() === 0)
        <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
            <p class="text-4xl mb-3">📭</p>
            <p class="text-lg font-medium text-gray-600">No requests yet.</p>
            <p class="text-sm mt-1">New requests will appear here when customers book your services.</p>
        </div>
    @endif
</div>
@endsection