@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Notifications</h2>

    <div class="space-y-4">
        @forelse(auth()->user()->notifications as $notification)
            @php
                $techId = $notification->data['technician_id'] ?? null;
                $tech = $techId ? \App\Models\User::find($techId) : null;
            @endphp
            
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                <div class="bg-emerald-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 3 3 0 016 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                
                <div class="flex-1">
                    <p class="text-sm font-semibold text-emerald-800">{{ $tech ? $tech->name : 'Fundipopote' }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? 'no message available' }}</p>
                    <span class="text-[10px] text-gray-400 mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500">No new notifications.</div>
        @endforelse
    </div>
</div>
@endsection