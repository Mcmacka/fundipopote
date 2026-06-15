@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="{{ route('technician.dashboard') }}" 
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Dashboard
            </a>
        </div>
        
        <div class="mb-6">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Job Overview</h2>
            <p class="text-gray-500 mt-1">Detailed information regarding the service request.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="p-6 border-b border-gray-50">
                <div class="flex items-center gap-2 mb-3 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="font-bold text-gray-800 text-lg">Description</h3>
                </div>
                <p class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                    {{ $booking->description }}
                </p>
            </div>

            <div class="p-6 border-b border-gray-50">
                <div class="flex items-center gap-2 mb-3 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <h3 class="font-bold text-gray-800 text-lg">Location</h3>
                </div>
                <p class="text-gray-600">{{ $booking->location_address }}</p>
            </div>

            <div class="p-6">
                <div class="flex items-center gap-2 mb-4 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <h3 class="font-bold text-gray-800 text-lg">Customer Contact</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Name</span>
                        <p class="text-gray-900 font-medium mt-1">{{ $booking->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Phone</span>
                        <p class="text-gray-900 font-medium mt-1">{{ $booking->customer->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="tel:{{ $booking->customer->phone ?? '#' }}" 
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Call Customer
                    </a>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">Service Pricing</h3>

    {{-- Tunabadilisha condition: Fomu inaonekana kama status SIYO completed --}}
    @if($booking->status !== 'completed')
        <form action="{{ route('technician.bookings.propose-price', $booking->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700">
                    {{ $booking->agreed_price > 0 ? 'Sasisha Bei ya Kazi (TZS)' : 'Weka Bei ya Kazi (TZS)' }}
                </label>
                <input type="number" name="agreed_price" 
                       value="{{ $booking->agreed_price > 0 ? $booking->agreed_price : '' }}" 
                       class="w-full p-3 border border-gray-300 rounded-xl" 
                       placeholder="Mfano: 50000" required>
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700">
                {{ $booking->agreed_price > 0 ? 'Sasisha Bei' : 'Tuma Bei kwa Mteja' }}
            </button>
        </form>
    @else
        {{-- Ujumbe huu unaonekana tu kazi ikiwa imekamilika --}}
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="font-bold text-green-800">Bei ya Mwisho: {{ number_format($booking->agreed_price) }} TZS</p>
        </div>
    @endif
</div>


{{-- SEHEMU MPYA: Maelezo (Notes) Yanayojitegemea --}}
<div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm">
    <form action="{{ route('technician.bookings.update-notes', $booking->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">
                Technician Notes (Internal & Customer Updates)
            </label>
            <textarea name="technician_notes" 
                      class="w-full p-3 border border-gray-300 rounded-xl" 
                      rows="3" 
                      placeholder="Andika maelezo ya kazi hapa...">{{ $booking->technician_notes }}</textarea>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-xl font-bold hover:bg-blue-700 transition">
            Save Notes Only
        </button>
    </form>
</div>

        </div>
    </div>
</div>
@endsection