@extends('layouts.app')
@section('title', 'Find a Technician')

@section('content')

{{-- ══════════ HERO ══════════ --}}
<section class="gradient-hero py-16 px-4 rounded-b-[2rem] shadow-md mb-8">
  <div class="max-w-4xl mx-auto text-center text-white mb-8">
    <h1 class="text-4xl md:text-5xl font-extrabold mb-3 tracking-tight">Find a Trusted Technician</h1>
    <p class="text-emerald-100 text-lg opacity-90">Professional services near you across Dar es Salaam and beyond</p>
  </div>

  {{-- Search Box Container --}}
  <div class="max-w-4xl mx-auto">
    <form method="GET" action="{{ route('customer.search') }}" id="search-form"
          class="bg-white rounded-2xl md:rounded-3xl shadow-xl p-4 flex flex-col md:flex-row gap-3 border border-gray-50">

      {{-- Search name --}}
      <div class="flex-1">
        <div class="flex items-center gap-2 border border-gray-100 bg-gray-50/50 rounded-2xl px-4 py-3 focus-within:bg-white focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100/50 transition-all duration-200">
          <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search technician name..."
            class="flex-1 text-sm outline-none bg-transparent text-gray-700 placeholder-gray-400">
        </div>
      </div>

      {{-- Category --}}
      <div class="md:w-48">
        <select name="category_id"
          class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition-all duration-200 cursor-pointer appearance-none">
          <option value="">All Services</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- Distance --}}
      <div class="md:w-36">
        <select name="radius_km"
          class="w-full border border-gray-100 bg-gray-50/50 rounded-2xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100/50 transition-all duration-200 cursor-pointer appearance-none">
          <option value="5"  {{ request('radius_km')==5  ?'selected':'' }}>5 km</option>
          <option value="10" {{ request('radius_km')==10 ?'selected':'' }}>10 km</option>
          <option value="20" {{ request('radius_km',20)==20?'selected':'' }}>20 km</option>
          <option value="50" {{ request('radius_km')==50 ?'selected':'' }}>50 km</option>
        </select>
      </div>

      <input type="hidden" name="latitude"  id="lat-inp" value="{{ request('latitude') }}">
      <input type="hidden" name="longitude" id="lng-inp" value="{{ request('longitude') }}">

      {{-- GPS + Search Buttons --}}
      <div class="flex gap-2">
        <button type="button" onclick="useGPS()"
          class="btn-outline px-4 py-3 text-sm font-medium rounded-2xl whitespace-nowrap flex items-center gap-2 border-gray-200 hover:bg-gray-50 transition active:scale-95">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
          </svg>
          GPS
        </button>
        <button type="submit" class="btn-primary px-8 py-3 rounded-2xl font-semibold text-sm text-white bg-emerald-600 hover:bg-emerald-700 transition active:scale-95 shadow-lg shadow-emerald-600/20 whitespace-nowrap">Search</button>
      </div>
    </form>
    <p id="gps-msg" class="text-center text-emerald-100 text-sm mt-3 hidden bg-emerald-800/40 py-2 px-4 rounded-xl inline-block mx-auto"></p>
  </div>
</section>

{{-- ══════════ CATEGORY PILLS ══════════ --}}
<section class="max-w-7xl mx-auto px-4 py-4 mb-4">
  <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-2">
    <a href="{{ route('customer.search', request()->except(['category_id','page'])) }}"
       class="flex-none px-5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 whitespace-nowrap
              {{ !request('category_id') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/10' : 'bg-white border border-gray-200 text-gray-600 hover:border-emerald-300 hover:text-emerald-600 hover:shadow-sm' }}">
      All Services
    </a>
    @foreach($categories as $cat)
      <a href="{{ route('customer.search', array_merge(request()->except(['category_id','page']), ['category_id'=>$cat->id])) }}"
         class="flex-none px-5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 whitespace-nowrap
                {{ request('category_id')==$cat->id ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/10' : 'bg-white border border-gray-200 text-gray-600 hover:border-emerald-300 hover:text-emerald-600 hover:shadow-sm' }}">
        {{ $cat->name }}
      </a>
    @endforeach
  </div>
</section>

{{-- ══════════ RESULTS ══════════ --}}
<section class="max-w-7xl mx-auto px-4 pb-16">

  {{-- Results header --}}
  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-xl font-bold text-gray-900 tracking-tight">
        {{ $technicians->total() }} {{ $technicians->total() == 1 ? 'Technician Found' : 'Technicians Found' }}
      </h2>
      @if(request('category_id'))
        <p class="text-sm text-gray-500 mt-0.5">
          in <span class="font-medium text-emerald-600">{{ $categories->find(request('category_id'))?->name }}</span>
          @if(request()->filled('latitude')) · sorted by distance @endif
        </p>
      @endif
    </div>
    @if(request()->hasAny(['search','category_id','latitude']))
      <a href="{{ route('customer.search') }}"
         class="text-sm font-medium text-gray-500 hover:text-red-600 border border-gray-200 hover:border-red-200 bg-white px-4 py-2 rounded-2xl shadow-sm transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Clear Filters
      </a>
    @endif
  </div>

  {{-- TECHNICIAN CARDS --}}
  @forelse($technicians as $profile)
    <div class="bg-white rounded-3xl border border-gray-100 p-6 mb-5 transition-all duration-300 hover:shadow-xl hover:shadow-gray-100/50 hover:-translate-y-0.5 border-b-2 border-b-gray-100/70">
      <div class="flex flex-col sm:flex-row gap-5">

        {{-- Avatar Container --}}
<div class="flex-shrink-0 mx-auto sm:mx-0">
  @if($profile->profile_photo_url)
    <img src="{{ $profile->profile_photo_url }}" 
         alt="{{ $profile->user->name }}" 
         class="w-32 h-32 rounded-2xl object-cover shadow-md">
  @else
    {{-- Fallback: Itaonyesha initials kama picha haipo --}}
    <div class="w-18 h-18 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold shadow-md shadow-emerald-500/20">
      {{ strtoupper(substr($profile->user->name, 0, 2)) }}
    </div>
  @endif
</div>

        {{-- Main Info --}}
        <div class="flex-1 min-w-0 text-center sm:text-left">
          <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start gap-3">
            <div>
              <h3 class="text-xl font-bold text-gray-900 tracking-tight">{{ $profile->user->name }}</h3>
              <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2 mt-1.5">
                <span class="px-3 py-1 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100/50">
                  {{ $profile->category->name }}
                </span>
                @if($profile->years_experience > 0)
                  <span class="px-3 py-1 text-xs font-semibold rounded-xl bg-blue-50 text-blue-700 border border-blue-100/50">
                    Experience: {{ $profile->years_experience }} {{ $profile->years_experience == 1 ? 'Year' : 'Years' }}
                  </span>
                @endif
              </div>
            </div>

            {{-- Rating --}}
            <div class="text-center sm:text-right">
              <div class="flex items-center justify-center sm:justify-end gap-0.5">
                @for($i=1;$i<=5;$i++)
                  <svg class="w-4 h-4 {{ $i<=round($profile->average_rating)?'text-amber-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                @endfor
              </div>
              <p class="text-xs font-medium text-gray-500 mt-1">
                {{ number_format($profile->average_rating,1) }}
                <span class="text-gray-400 font-normal">({{ $profile->total_reviews }} {{ $profile->total_reviews == 1 ? 'review' : 'reviews' }})</span>
              </p>
            </div>
          </div>

          {{-- Bio --}}
          @if($profile->bio)
            <p class="text-sm text-gray-600 mt-4 p-3.5 bg-gray-50/70 rounded-2xl leading-relaxed text-left">{{ $profile->bio }}</p>
          @endif

          {{-- Meta Fields --}}
          <div class="flex flex-wrap justify-center sm:justify-start gap-x-5 gap-y-2 mt-4 text-xs font-medium text-gray-400">
            <div class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-xl">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              </svg>
              <span class="text-gray-600">{{ $profile->location_name ?? 'Dar es Salaam' }}</span>
            </div>
            @if(isset($profile->distance_km))
              <div class="flex items-center gap-1.5 bg-emerald-50 px-2.5 py-1 rounded-xl text-emerald-700 font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                {{ number_format($profile->distance_km, 1) }} km away
              </div>
            @endif
            <div class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-xl">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              <span class="text-gray-600">Serves up to {{ $profile->service_radius_km }} km radius</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-5 border-t border-gray-100">
        @if($profile->user->phone)
          <a href="tel:{{ $profile->user->phone }}"
             class="inline-flex items-center justify-center gap-2 border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-3 rounded-2xl transition active:scale-95">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            {{ $profile->user->phone }}
          </a>
        @endif

        <a href="{{ route('customer.technician.show', $profile->user_id) }}"
           class="inline-flex items-center justify-center gap-2 border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-3 rounded-2xl transition active:scale-95">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          View Profile
        </a>

        <a href="{{ route('customer.bookings.create', ['technician_id' => $profile->user_id]) }}"
           class="flex-1 inline-flex items-center justify-center gap-2 text-white bg-emerald-600 hover:bg-emerald-700 font-semibold text-sm px-5 py-3 rounded-2xl transition shadow-lg shadow-emerald-600/10 active:scale-95">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          Book Now
        </a>
      </div>
    </div>
  @empty
    {{-- Empty State Container --}}
    <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-xl shadow-gray-100/40">
      <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-100">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <h3 class="text-lg font-bold text-gray-800">No technicians found</h3>
      <p class="text-sm text-gray-400 mt-1 mb-6 max-w-sm mx-auto">Try adjusting your distance radius, changing categories, or searching for a different keyword</p>
      <a href="{{ route('customer.search') }}" class="btn-primary inline-flex px-8 py-3 rounded-2xl font-semibold text-sm text-white bg-emerald-600 hover:bg-emerald-700 transition active:scale-95 shadow-lg shadow-emerald-600/20">
        Show All Technicians
      </a>
    </div>
  @endforelse

  {{-- Pagination --}}
  <div class="mt-8">{{ $technicians->withQueryString()->links() }}</div>
</section>

@push('scripts')
<script>
function useGPS() {
  const msg = document.getElementById('gps-msg');
  msg.classList.remove('hidden');
  msg.textContent = 'Acquiring your location...';
  if (!navigator.geolocation) { msg.textContent = 'GPS is not supported by this browser.'; return; }
  navigator.geolocation.getCurrentPosition(
    pos => {
      document.getElementById('lat-inp').value = pos.coords.latitude.toFixed(6);
      document.getElementById('lng-inp').value = pos.coords.longitude.toFixed(6);
      msg.textContent = '✓ Location acquired! Searching nearby technicians...';
      setTimeout(() => document.getElementById('search-form').submit(), 600);
    },
    () => { msg.textContent = '✗ Unable to retrieve location. Please enable your device GPS.'; }
  );
}
</script>
@endpush
@endsection