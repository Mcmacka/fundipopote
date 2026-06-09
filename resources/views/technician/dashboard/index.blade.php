@extends('layouts.technician')
@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-6 md:px-6 lg:px-8 bg-gray-50 min-h-screen">

    {{-- Header with professional spacing --}}
    <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
            <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        @if($subscription)
            <div class="flex items-center gap-2 bg-white shadow-sm rounded-full px-4 py-2 border border-gray-200">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                <span class="text-sm font-medium text-gray-700">Active until {{ $subscription->expires_at->format('d M Y') }}</span>
            </div>
        @endif
    </div>

    {{-- Stats Row – clean text cards, no icons --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        {{-- Pending --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wide">New Requests</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingBookings->count() }}</p>
            </div>
        </div>

        {{-- Active --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wide">In Progress</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $activeBookings->count() }}</p>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wide">Completed</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $completedCount }}</p>
            </div>
        </div>
    </div>

    {{-- Two column layout: main content (bookings) + optional sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main area: Pending & Active bookings --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Pending Bookings Card --}}
            @if($pendingBookings->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">New Requests</h2>
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">{{ $pendingBookings->count() }} pending</span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($pendingBookings as $booking)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex flex-wrap justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-semibold text-gray-900">{{ $booking->customer->name }}</span>
                                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ $booking->booking_code }}</span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ $booking->location_address }}
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $booking->description }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('technician.bookings.update', $booking->id) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <input type="hidden" name="agreed_price" value="0">
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                                Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('technician.bookings.update', $booking->id) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <input type="hidden" name="reason" value="Unavailable">
                                            <button type="submit" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-lg transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Active Bookings Card --}}
            @if($activeBookings->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">Tasks In Progress</h2>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ $activeBookings->count() }} active</span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($activeBookings as $booking)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex flex-wrap justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900">{{ $booking->customer->name }}</div>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500 mt-1">
                                            <span>{{ $booking->location_address }}</span>
                                            @if($booking->agreed_price)
                                                <span class="text-emerald-600 font-medium">
                                                    TZS {{ number_format($booking->agreed_price) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($booking->status === 'accepted' || $booking->status === 'in_progress')
                                            <div class="mt-2">
                                                <a href="{{ route('technician.bookings.show', $booking->id) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                                    View full details →
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('technician.bookings.update', $booking->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                            Complete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Empty state --}}
            @if($pendingBookings->count() === 0 && $activeBookings->count() === 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-gray-400 text-2xl">—</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800">No active requests</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-sm mx-auto">When customers book your services, new requests will appear here.</p>
                </div>
            @endif
        </div>

        {{-- Right sidebar: Profile / Quick info / Tips --}}
        <div class="space-y-6">
            {{-- Technician profile card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center border border-gray-200">
                        @if(auth()->user()->technicianProfile && auth()->user()->technicianProfile->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->technicianProfile->profile_photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->technicianProfile?->category?->name ?? 'Service Technician' }}</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Member since</span>
                        <span class="text-gray-700">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Completed jobs</span>
                        <span class="font-medium text-gray-800">{{ $completedCount }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick tip card --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-5">
                <p class="font-semibold text-gray-800">Quick tip</p>
                <p class="text-xs text-gray-600 mt-1">Always confirm the service location and agreed price before starting a job.</p>
            </div>
        </div>
    </div>
</div>
@endsection