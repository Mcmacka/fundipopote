@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    
    {{-- TOP HEADING LAYER (PREMIUM MINIMALIST) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <svg class="w-7 h-7 text-slate-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                </svg>
                My Service Bookings
            </h1>
            <p class="text-xs font-medium text-slate-400 mt-1.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"/>
                </svg>
                Track and manage your service requests with technicians across Tanzania.
            </p>
        </div>

        @if(!$bookings->isEmpty())
            <a href="{{ route('customer.search') }}" 
               class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors shadow-sm self-start sm:self-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                New Booking
            </a>
        @endif
    </div>

    {{-- BOOKINGS CONTENT INFRASTRUCTURE --}}
    @if($bookings->isEmpty())
        {{-- Empty State (Clean & Minimalist) --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm max-w-md mx-auto mt-12">
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3m0 0c2.917 0 5.747.294 8.5.862m-21 10.398c0-.552.448-1 1-1h6.25a1 1 0 011 1v3.834a1 1 0 01-1 1H2.5a1 1 0 01-1-1v-3.834z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900">No bookings found</h3>
            <p class="text-xs text-slate-400 mt-1 mb-5 leading-relaxed">You haven't requested any technical services yet. Find a trusted technician to get started.</p>
            
            <a href="{{ route('customer.search') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-bold px-5 py-3 rounded-xl shadow-sm transition-colors">
                Explore Technicians
            </a>
        </div>
    @else
        {{-- Bookings Stack List --}}
        <div class="space-y-3.5">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:border-slate-200 transition-all flex flex-col md:flex-row md:items-center justify-between gap-5 group">
                    
                    {{-- Left Pane: Info Content --}}
                    <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
                        {{-- Avatar Container --}}
                        <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white text-sm font-bold overflow-hidden flex-shrink-0 border border-slate-800 shadow-sm">
                            @if($booking->technician->technicianProfile && $booking->technician->technicianProfile->profile_photo)
                                <img src="{{ asset('storage/' . $booking->technician->technicianProfile->profile_photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($booking->technician->name, 0, 2)) }}
                            @endif
                        </div>
                        
                        {{-- Metadata Group --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-sm tracking-tight truncate">{{ $booking->technician->name }}</h3>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-800 text-[10px] font-medium rounded-md">
                                    {{ $booking->category->name }}
                                </span>
                            </div>
                            
                            {{-- Code & Description Wrapper --}}
                            <div class="flex flex-col sm:flex-row sm:items-center gap-x-3 gap-y-1 mt-1">
                                <div class="flex items-center gap-1 text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                    <span>Code:</span>
                                    <span class="text-slate-700 font-mono">{{ $booking->booking_code }}</span>
                                </div>
                                @if($booking->description)
                                    <span class="hidden sm:inline text-slate-200 text-xs">•</span>
                                    <p class="text-xs text-slate-500 truncate max-w-md font-normal">{{ $booking->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Pane: Status & Navigation Hub --}}
                    <div class="flex items-center justify-between md:justify-end gap-6 border-t border-slate-50 pt-3 md:pt-0 md:border-0">
                        {{-- Dynamic Status Badge --}}
                        <div>
                            @switch($booking->status)
                                @case('pending')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-700 bg-amber-50/60 border border-amber-100 rounded-lg px-2.5 py-1 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Pending
                                    </span>
                                    @break
                                @case('accepted')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-sky-700 bg-sky-50/60 border border-sky-100 rounded-lg px-2.5 py-1 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        In Progress
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50/60 border border-emerald-100 rounded-lg px-2.5 py-1 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Completed
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        {{ $booking->status }}
                                    </span>
                            @endswitch
                        </div>
                        
                        {{-- Action Button --}}
                        <a href="{{ route('customer.bookings.show', $booking->id) }}" 
                           class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-sm">
                            Manage
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection