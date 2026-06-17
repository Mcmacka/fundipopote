@extends('layouts.app')
@section('title', 'Book a Service')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

  <a href="{{ route('customer.technician.show', $profile->user_id) }}"
     class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-emerald-600 transition mb-6 font-medium">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to Profile
  </a>

  {{-- Technician card --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6 shadow-md shadow-gray-100/50">
    <div class="flex items-center gap-4">
      {{-- Picha ya fundi --}}
      @if($profile->profile_photo)
        <img src="{{ asset('storage/' . $profile->profile_photo) }}" 
             alt="{{ $profile->user->name }}"
             class="w-14 h-14 rounded-2xl object-cover shadow-md shadow-emerald-500/10 flex-shrink-0">
      @else
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-emerald-500/10 flex-shrink-0"
             style="background:linear-gradient(135deg,#059669,#0d9488)">
          {{ strtoupper(substr($profile->user->name, 0, 2)) }}
        </div>
      @endif

      <div class="flex-1 min-w-0">
        <h3 class="font-bold text-gray-900 text-lg tracking-tight truncate">{{ $profile->user->name }}</h3>
        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
          <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-100/40 px-2.5 py-1 rounded-xl font-semibold">
            {{ $profile->category->name }}
          </span>
          <span class="flex items-center gap-1 text-xs text-amber-500 bg-amber-50/50 px-2 py-1 rounded-xl border border-amber-100/30">
            @for($i=1;$i<=5;$i++)
              <svg class="w-3 h-3 {{ $i<=round($profile->average_rating)?'text-amber-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            @endfor
            <span class="text-gray-600 font-bold ml-0.5">{{ number_format($profile->average_rating, 1) }}</span>
          </span>
          <span class="text-xs text-gray-500 flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-xl border border-gray-100">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            {{ $profile->location_name ?? 'Dar es Salaam' }}
          </span>
        </div>
      </div>
      @if($profile->user->phone)
        <a href="tel:{{ $profile->user->phone }}"
           class="inline-flex items-center gap-1.5 text-xs border border-gray-200 text-gray-600 px-3.5 py-2 rounded-xl hover:bg-gray-50 transition font-medium flex-shrink-0 shadow-sm active:scale-95">
          <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
          </svg>
          Call
        </a>
      @endif
    </div>
  </div>

  {{-- Booking form --}}
  <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/40 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
      <h1 class="text-xl font-bold text-gray-900 tracking-tight">Book a Service</h1>
      <p class="text-sm text-gray-500 mt-0.5">Fill in the details below and the technician will respond shortly.</p>
    </div>

    <div class="p-6">
      <form method="POST" action="{{ route('customer.bookings.store') }}">
        @csrf
        <input type="hidden" name="technician_id" value="{{ $profile->user_id }}">
        <input type="hidden" name="category_id"   value="{{ $profile->category_id }}">

        {{-- Description --}}
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-800 mb-1.5">
            Describe the Problem <span class="text-red-500">*</span>
          </label>
          <textarea name="description" rows="4" required minlength="10" maxlength="1000"
            placeholder="e.g. The electrical switch in my bedroom stopped working and the whole room has no power. I need urgent help..."
            class="w-full border border-gray-200 bg-gray-50/30 rounded-2xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition duration-200 shadow-sm resize-none">{{ old('description') }}</textarea>
          @error('description')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        {{-- Location Address --}}
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-800 mb-1.5">
            Your Address <span class="text-red-500">*</span>
          </label>
          <input type="text" name="location_address" value="{{ old('location_address') }}"
            placeholder="e.g. House No. 15, Mikocheni B" required maxlength="255"
            class="w-full border border-gray-200 bg-gray-50/30 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition shadow-sm">
        </div>

        {{-- GPS Coordinates --}}
        <div class="mb-5">
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-sm font-semibold text-gray-800">GPS Coordinates <span class="text-red-500">*</span></label>
            <button type="button" id="btn-gps" onclick="grabGPS()"
              class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-xl transition border border-emerald-100/30 active:scale-95">
              Auto-detect
            </button>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <input type="hidden" name="location_lat" id="lat-f" value="{{ old('location_lat') }}" placeholder="Latitude" required readonly class="w-full border border-gray-200 bg-gray-100/60 rounded-2xl px-4 py-3 text-sm">
            <input type="hidden" name="location_lng" id="lng-f" value="{{ old('location_lng') }}" placeholder="Longitude" required readonly class="w-full border border-gray-200 bg-gray-100/60 rounded-2xl px-4 py-3 text-sm">
          </div>
        </div>

        <button type="submit"
          class="w-full text-white bg-emerald-600 hover:bg-emerald-700 font-bold py-3.5 rounded-2xl shadow-lg transition active:scale-[0.99]">
          Send Booking Request
        </button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    function grabGPS() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat-f').value = position.coords.latitude;
                document.getElementById('lng-f').value = position.coords.longitude;
                alert("Location captured successfully!");
            }, function(error) {
                alert("Error: " + error.message);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>
@endpush
@endsection