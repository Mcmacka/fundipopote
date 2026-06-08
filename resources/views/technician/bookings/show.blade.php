@extends('layouts.technician')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    {{-- Maelezo ya kazi yanaonekana kwa kila mtu aliyepewa kazi --}}
    <div class="p-6 bg-white rounded-xl shadow-sm border">
        <h2 class="font-bold text-lg">Job Description</h2>
        <p>{{ $booking->description }}</p>
        <p><strong>Address:</strong> {{ $booking->location_address }}</p>
    </div>

    {{-- HAPA NDIPO ULINZI ULIPO --}}
    @if(in_array($booking->status, ['accepted', 'in_progress', 'completed']))
        {{-- Hii sehemu itaonekana tu kama kazi imekubaliwa --}}
        <div class="mt-6 p-6 bg-emerald-50 rounded-xl border border-emerald-200">
            <h3 class="font-bold text-emerald-900">Customer Details</h3>
            <p><strong>Name:</strong> {{ $booking->customer->name }}</p>
            <p><strong>Phone:</strong> {{ $booking->customer->phone }}</p>
        </div>
    @else
        {{-- Kazi iko 'pending', maelezo ya mteja yamefichwa --}}
        <div class="mt-6 p-6 bg-amber-50 rounded-xl border border-amber-200">
            <p class="text-amber-800">Please <strong>Accept</strong> the job to reveal customer contact details.</p>
            
            {{-- Button ya Accept --}}
            <form action="{{ route('technician.bookings.update', $booking->id) }}" method="POST" class="mt-4">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="accepted">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold">
                    Accept Job
                </button>
            </form>
        </div>
    @endif
</div>
@endsection