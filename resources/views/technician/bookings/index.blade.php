@extends('layouts.technician')
@section('title', 'Job Requests')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">All Job Requests</h1>

    <div class="space-y-4">
        @forelse($bookings as $booking)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs text-gray-400 font-mono">{{ $booking->booking_code }}</span>
                        <h3 class="font-semibold text-gray-900 mt-0.5">{{ $booking->customer->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $booking->category->name }} · {{ $booking->location_address }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($booking->description, 100) }}</p>
                        
                        @if($booking->agreed_price)
                            <p class="text-sm font-medium text-emerald-600 mt-1">TZS {{ number_format($booking->agreed_price) }}</p>
                        @endif

                        {{-- KITUFE CHA KUTAZAMA TAARIFA ZAIDI --}}
                        <a href="{{ route('technician.bookings.show', $booking->id) }}" 
                           class="inline-block mt-3 text-xs font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg transition">
                            View Request Details →
                        </a>
                    </div>

                    <span class="text-xs px-3 py-1 rounded-full font-medium
                        {{ $booking->status === 'accepted'    ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $booking->status === 'pending'     ? 'bg-amber-50 text-amber-700'     : '' }}
                        {{ $booking->status === 'rejected'    ? 'bg-red-50 text-red-600'         : '' }}
                        {{ $booking->status === 'completed'   ? 'bg-blue-50 text-blue-700'       : '' }}
                        {{ $booking->status === 'in_progress' ? 'bg-purple-50 text-purple-700'   : '' }}">
                        {{ match($booking->status) {
                            'pending'     => ' Pending',
                            'accepted'    => ' Accepted',
                            'rejected'    => ' Rejected',
                            'in_progress' => ' In Progress',
                            'completed'   => ' Completed',
                            default       => ucfirst($booking->status),
                        } }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-gray-600">No requests yet.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
</div>
@endsection