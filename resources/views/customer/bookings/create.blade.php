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
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-emerald-500/10 flex-shrink-0"
           style="background:linear-gradient(135deg,#059669,#0d9488)">
        {{ strtoupper(substr($profile->user->name, 0, 2)) }}
      </div>
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
            class="w-full border border-gray-200 bg-gray-50/30 rounded-2xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 resize-none transition duration-200 shadow-sm">{{ old('description') }}</textarea>
          @error('description')
            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ $message }}
            </p>
          @enderror
          <p class="text-xs text-gray-400 mt-1.5">Minimum 10 characters. Be as detailed as possible.</p>
        </div>

        {{-- Location Address --}}
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-800 mb-1.5">
            Your Address <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              </svg>
            </div>
            <input type="text" name="location_address"
              value="{{ old('location_address') }}"
              placeholder="e.g. House No. 15, Mikocheni B, near Shoprite"
              required maxlength="255"
              class="w-full border border-gray-200 bg-gray-50/30 rounded-2xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition duration-200 shadow-sm">
          </div>
          @error('location_address')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        {{-- GPS Coordinates --}}
        <div class="mb-5">
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-sm font-semibold text-gray-800">GPS Coordinates <span class="text-red-500">*</span></label>
            <button type="button" id="btn-gps" onclick="grabGPS()"
              class="text-xs text-emerald-600 hover:text-emerald-700 font-bold flex items-center gap-1 bg-emerald-50 px-2.5 py-1 rounded-xl transition border border-emerald-100/30 active:scale-95">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Auto-detect Location
            </button>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <input type="text" name="location_lat" id="lat-f" value="{{ old('location_lat') }}"
                placeholder="Latitude" required readonly
                class="w-full border border-gray-200 bg-gray-100/60 rounded-2xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition duration-200 shadow-sm">
            </div>
            <div>
              <input type="text" name="location_lng" id="lng-f" value="{{ old('location_lng') }}"
                placeholder="Longitude" required readonly
                class="w-full border border-gray-200 bg-gray-100/60 rounded-2xl px-4 py-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition duration-200 shadow-sm">
            </div>
          </div>
          
          @error('location_lat')
            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              Please allow GPS auto-detection to help the technician navigate to your house.
            </p>
          @enderror
          
          <p id="gps-st" class="text-xs font-medium mt-2 bg-gray-50 rounded-xl px-3 py-1.5 inline-block hidden"></p>
        </div>

        {{-- Scheduled date --}}
        <div class="mb-6">
          <label class="block text-sm font-semibold text-gray-800 mb-1.5">
            Preferred Date & Time <span class="text-gray-400 font-normal text-xs">(optional)</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <input type="datetime-local" name="scheduled_at"
              value="{{ old('scheduled_at') }}"
              min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
              class="w-full border border-gray-200 bg-gray-50/30 rounded-2xl pl-11 pr-4 py-3 text-sm text-gray-700 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition duration-200 shadow-sm cursor-pointer">
          </div>
          <p class="text-xs text-gray-400 mt-1.5">Leave empty and the technician will contact you to schedule.</p>
        </div>

        {{-- How it works note --}}
        <div class="bg-amber-50/60 border border-amber-100/70 rounded-2xl p-4 mb-6">
          <h4 class="text-sm font-bold text-amber-900 tracking-tight mb-2.5">How it works</h4>
          <div class="space-y-2">
            @foreach(['You submit this request','Technician reviews and accepts with an initial estimate','Technician arrives — you agree on a final price','Work is done — you pay after confirming completion'] as $i => $step)
              <div class="flex items-start gap-2.5 text-xs text-amber-800 font-medium leading-relaxed">
                <span class="w-5 h-5 rounded-xl bg-amber-200/70 text-amber-900 flex items-center justify-center font-bold flex-shrink-0 mt-0.5 shadow-sm border border-amber-300/20">{{ $i+1 }}</span>
                <span class="pt-0.5">{{ $step }}</span>
              </div>
            @endforeach
          </div>
        </div>

        <button type="submit"
          class="w-full text-white bg-emerald-600 hover:bg-emerald-700 font-bold py-3.5 rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20 transition active:scale-[0.99]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
          Send Booking Request
        </button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function grabGPS() {
  const st = document.getElementById('gps-st');
  const btn = document.getElementById('btn-gps');
  
  st.classList.remove('hidden');
  st.style.color = '#4b5563';
  st.style.backgroundColor = '#f9fafb';
  st.textContent = 'Locating your hardware GPS...';
  btn.disabled = true;

  if (!navigator.geolocation) { 
    st.style.color = '#b91c1c'; 
    st.style.backgroundColor = '#fef2f2';
    st.textContent = 'Geolocation features are not supported by your browser.'; 
    btn.disabled = false;
    return; 
  }

  navigator.geolocation.getCurrentPosition(
    p => {
      document.getElementById('lat-f').value = p.coords.latitude;
      document.getElementById('lng-f').value = p.coords.longitude;
      st.style.color = '#047857';
      st.style.backgroundColor = '#ecfdf5';
      st.textContent = '✓ Precise GPS coordinates captured successfully!';
      btn.disabled = false;
    },
    (error) => { 
      btn.disabled = false;
      st.style.color = '#b91c1c'; 
      st.style.backgroundColor = '#fef2f2';
      
      switch(error.code) {
        case error.PERMISSION_DENIED:
          st.textContent = '✗ Request denied. Please toggle your device setting to allow location access.';
          break;
        case error.POSITION_UNAVAILABLE:
          st.textContent = '✗ Location network unavailable. Move to an open space or turn on Wi-Fi/Mobile Data.';
          break;
        case error.TIMEOUT:
          st.textContent = '✗ Location request timed out. Please click auto-detect again.';
          break;
        default:
          st.textContent = '✗ An unhandled error occurred while requesting coordinates.';
      }
    },
    { enableHighAccuracy: true, timeout: 15000 }
  );
}
</script>
@endpush
@endsection