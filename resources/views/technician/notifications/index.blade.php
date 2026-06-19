@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Your Notifications</h1>
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $message = $data['message'] ?? 'You have a new update.';
                $bookingId = $data['booking_id'] ?? null;
                
                // Safety check: Angalia kama $bookingId ipo kabla ya kutengeneza route
                $viewRoute = '#';
                if ($bookingId) {
                    $viewRoute = auth()->user()->isTechnician() 
                        ? route('technician.bookings.show', $bookingId) 
                        : route('customer.bookings.show', $bookingId);
                }
            @endphp

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between gap-6 hover:border-gray-200 transition-all">
                <div class="flex-1">
                    <p class="text-sm text-gray-800 leading-relaxed font-medium">
                        {{ $message }}
                    </p>
                    <span class="text-xs text-gray-400 mt-2 block">
                        {{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Sasa hivi' }}
                    </span>
                </div>

                @if($bookingId)
                    <div class="shrink-0">
                        <a href="{{ $viewRoute }}" class="inline-block px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
                            View
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-gray-500 font-medium">No notifications found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>
@endsection