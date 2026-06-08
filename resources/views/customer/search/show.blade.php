@extends('layouts.app')
@section('title', $profile->user->name . ' — Profile')

@section('content')

{{-- TOP PROFILE BLOCK (PREMIUM MINIMALIST) --}}
<div class="bg-white border-b border-slate-100">
  <div class="max-w-5xl mx-auto px-6 pt-8 pb-10">
    
    {{-- Back Link --}}
    <a href="{{ route('customer.search') }}"
       class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-slate-900 mb-8 transition-colors group">
      <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Back to Search
    </a>

    <div class="flex flex-col md:flex-row gap-8 items-start md:items-center justify-between">
      {{-- Identity Info Group --}}
      <div class="flex flex-col sm:flex-row gap-6 items-center text-center sm:text-left">
        {{-- Avatar Box --}}
        <div class="w-24 h-24 bg-gradient-to-tr from-emerald-500 to-emerald-400 p-[3px] rounded-2xl shadow-md shadow-emerald-500/10 flex-shrink-0">
          <div class="w-full h-full bg-slate-900 rounded-[13px] flex items-center justify-center text-white text-2xl font-bold tracking-wide">
            {{ strtoupper(substr($profile->user->name, 0, 2)) }}
          </div>
        </div>

        {{-- Meta Badges --}}
        <div>
          <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $profile->user->name }}</h1>
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6.267 3.455a.75.75 0 00-.708.522L4.547 7.222a.75.75 0 01-.428.46l-3.322 1.33a.75.75 0 00-.472.859l.74 3.69a.75.75 0 00.582.593l3.642.73a.75.75 0 01.562.438l1.449 3.26a.75.75 0 00.865.433l3.655-.913a.75.75 0 01.624.115l2.96 2.07a.75.75 0 001.03-.182l2.256-3.01a.75.75 0 01.62-.27l3.754.21a.75.75 0 00.757-.594l1.137-3.582a.75.75 0 01.378-.499l3.228-1.74a.75.75 0 00.322-.926l-1.574-3.411a.75.75 0 00-.693-.435l-3.71-.054a.75.75 0 01-.58-.337L15.34 3.73a.75.75 0 00-.862-.315l-3.572 1.168a.75.75 0 01-.643-.075L7.147 3.51a.75.75 0 00-.88.055zM13.5 8.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
          </div>
          
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5">
            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 font-medium text-xs rounded-md">
              {{ $profile->category->name }}
            </span>
            @if($profile->years_experience > 0)
              <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 font-medium text-xs rounded-md">
                {{ $profile->years_experience }} Yrs Exp
              </span>
            @endif
            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 font-semibold text-xs rounded-md">
              Verified & Active
            </span>
          </div>

          {{-- Real Reviews / Rating Row --}}
          <div class="flex items-center justify-center sm:justify-start gap-2 mt-3 text-sm">
            <div class="flex text-amber-400">
              @for($i=1;$i<=5;$i++)
                <svg class="w-4 h-4 {{ $i<=round($profile->average_rating)?'fill-current':'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                  <path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.21.814.675 1.458 1.394 1.018L10 15.547l4.183 2.512c.719.44 1.604-.204 1.394-1.018l-1.196-4.622 3.6-3.102c.635-.544.297-1.584-.536-1.651l-4.752-.382-1.83-4.4z"/>
                </svg>
              @endfor
            </div>
            <span class="font-bold text-slate-800 text-xs">{{ number_format($profile->average_rating, 1) }}</span>
            <span class="text-slate-400 text-xs">({{ $profile->total_reviews }} Reviews)</span>
          </div>
        </div>
      </div>

      {{-- Call to Actions --}}
      <div class="flex flex-col sm:flex-row md:flex-col gap-2.5 w-full md:w-auto mt-6 md:mt-0">
        <a href="{{ route('customer.bookings.create', ['technician_id' => $profile->user_id]) }}"
           class="w-full md:w-52 px-5 py-3 bg-emerald-500 text-slate-950 font-bold rounded-xl hover:bg-emerald-400 transition-colors text-center text-sm shadow-sm shadow-emerald-500/10">
          Book Appointment
        </a>
        @if($profile->user->phone)
          <a href="tel:{{ $profile->user->phone }}"
             class="w-full md:w-52 px-5 py-3 bg-slate-50 hover:bg-slate-100 text-slate-800 font-semibold rounded-xl border border-slate-200 transition-colors text-center text-sm">
            Call: {{ $profile->user->phone }}
          </a>
        @endif
      </div>
    </div>

  </div>
</div>

{{-- GRID DETAILS FEED LAYOUT --}}
<div class="max-w-5xl mx-auto px-6 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

    {{-- SIDE DETAILS COLUMN --}}
    <div class="lg:col-span-1 space-y-6">
      {{-- About Box --}}
      @if($profile->bio)
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7 0 3.75 3.75 0 017 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
          Bio Profile
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed font-normal">{{ $profile->bio }}</p>
      </div>
      @endif

      {{-- Structured Meta List Card --}}
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3m0 0c2.917 0 5.747.294 8.5.862m-21 10.398c0-.552.448-1 1-1h6.25a1 1 0 011 1v3.834a1 1 0 01-1 1H2.5a1 1 0 01-1-1v-3.834z"/></svg>
          Work Specifications
        </h3>
        
        <div class="space-y-3.5 text-xs">
          {{-- Item Row 1 --}}
          <div class="flex items-center justify-between py-1 border-b border-slate-50">
            <span class="text-slate-500 font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"/></svg>
              Base Location
            </span>
            <span class="text-slate-800 font-semibold">{{ $profile->location_name ?? 'Dar es Salaam' }}</span>
          </div>

          {{-- Item Row 2 --}}
          <div class="flex items-center justify-between py-1 border-b border-slate-50">
            <span class="text-slate-500 font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.67 2.67 0 0021 17.25l-5.83-5.83m-4.33 4.33l-5.83-5.83M11.42 15.17l2.42-2.42M11.42 15.17l-2.42-2.42m0 0L3.17 4.5a2.67 2.67 0 013.75-3.75l5.83 5.83m-4.33 4.33l2.42-2.42m0 0l5.83-5.83M17.25 3.17L21 6.92"/></svg>
              Specialization
            </span>
            <span class="text-slate-800 font-semibold">{{ $profile->category->name }}</span>
          </div>

          {{-- Item Row 3 --}}
          <div class="flex items-center justify-between py-1 border-b border-slate-50">
            <span class="text-slate-500 font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Experience
            </span>
            <span class="text-slate-800 font-semibold">{{ $profile->years_experience }} Years Active</span>
          </div>

          {{-- Item Row 4 --}}
          <div class="flex items-center justify-between py-1">
            <span class="text-slate-500 font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.691.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.691-.159 1.006 0l4.994 2.497c.317.158.691.158 1.006 0z"/></svg>
              Coverage Radius
            </span>
            <span class="text-slate-800 font-semibold">Max {{ $profile->service_radius_km }} KM</span>
          </div>
        </div>
      </div>
    </div>

    {{-- PORTFOLIO CASES MAIN FEED --}}
    <div class="lg:col-span-2 space-y-5">
      <h2 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2 mb-1">
        <svg class="w-4 h-4 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 012.25 2.25v4.5A2.25 2.25 0 0119.5 21h-15A2.25 2.25 0 012 18.75v-4.5a2.25 2.25 0 012.25-2.25zm0-4.5h18A2.25 2.25 0 0022 9.5v-4.5A2.25 2.25 0 0019.5 2.75h-15A2.25 2.25 0 002 5v4.5a2.25 2.25 0 002.25 2.25z"/></svg>
        Recent Handled Projects
      </h2>

      @if($works->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          
          @php $containerIndex = 0; @endphp
          @foreach($works->groupBy(function($item) { return $item->title . $item->created_at->format('YMDHis'); }) as $group)
            @php $firstWork = $group->first(); @endphp
            
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between group/card hover:border-slate-200 transition-all">
                {{-- Card Header --}}
                <div class="p-4 bg-white border-b border-slate-50">
                    <h3 class="text-xs font-bold text-slate-900 truncate tracking-tight">{{ $firstWork->title }}</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $firstWork->created_at->diffForHumans() }}</p>
                </div>

                {{-- Image Display Frame --}}
                <div class="relative overflow-hidden aspect-[4/3] bg-slate-900">
                    <div id="customer-slider-{{ $containerIndex }}" 
                         class="flex h-full overflow-x-auto snap-x snap-mandatory scrollbar-none scroll-smooth">
                        
                        @foreach($group as $photo)
                            <div class="min-w-full h-full flex-shrink-0 snap-start relative cursor-zoom-in"
                                 onclick="openGroupModal({{ $containerIndex }}, {{ $loop->index }})">
                                <img src="{{ asset('storage/' . $photo->image_path) }}" 
                                     alt="Portfolio Case Media" 
                                     class="w-full h-full object-cover select-none pointer-events-none group-hover/card:scale-[1.01] transition-transform duration-500">
                                
                                @if($group->count() > 1)
                                    <div class="absolute top-3 right-3 bg-slate-900/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-md tracking-wider">
                                        {{ $loop->iteration }}/{{ $group->count() }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Left/Right Controls --}}
                    @if($group->count() > 1)
                        <button onclick="scrollCustomerSlider({{ $containerIndex }}, 'left')" 
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white text-slate-900 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover/card:opacity-100 transition backdrop-blur-sm z-20 hidden sm:flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="scrollCustomerSlider({{ $containerIndex }}, 'right')" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white text-slate-900 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover/card:opacity-100 transition backdrop-blur-sm z-20 hidden sm:flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </div>

                {{-- Caption Summary Section --}}
                @if($firstWork->description)
                    <div class="p-4 bg-slate-50/50 border-t border-slate-50">
                        <p class="text-[11px] text-slate-500 leading-relaxed font-normal line-clamp-2">
                            {{ $firstWork->description }}
                        </p>
                    </div>
                @endif
            </div>
            @php $containerIndex++; @endphp
          @endforeach

        </div>
      @else
        <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-12 text-center">
          <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
          </div>
          <p class="text-xs font-bold text-slate-700">No project showcase media available</p>
        </div>
      @endif
    </div>
  </div>
</div>

{{-- LIGHTBOX OVERLAY GLASSMORPHIC COMPONENT --}}
<div id="custom-lightbox" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xl z-50 hidden flex flex-col justify-between p-6 transition-all duration-300" onclick="closeGroupModal(event)">
  
  {{-- Header Row --}}
  <div class="w-full flex justify-between items-center text-white max-w-4xl mx-auto py-2">
    <div>
        <h3 id="modal-work-title" class="font-bold text-sm tracking-tight text-slate-900 bg-white px-4 py-1.5 rounded-xl border border-slate-100 shadow-sm inline-block sm:block sm:bg-transparent sm:p-0 sm:border-none sm:shadow-none sm:text-white"></h3>
        <p id="modal-work-date" class="text-[10px] text-slate-500 font-semibold mt-1 sm:mt-0.5 sm:text-slate-400 pl-4 sm:pl-0"></p>
    </div>
    <button onclick="closeGroupModal()" class="text-slate-800 hover:text-slate-950 bg-white text-sm w-9 h-9 flex items-center justify-center rounded-xl transition-colors border border-slate-200 shadow-sm sm:bg-white/10 sm:text-slate-300 sm:hover:text-white sm:border-white/5 sm:shadow-none">✕</button>
  </div>

  {{-- Stage Display --}}
  <div class="relative max-w-4xl w-full flex-1 flex items-center justify-center mx-auto my-4" onclick="event.stopPropagation()">
    
    <button id="modal-btn-prev" onclick="changeModalPhoto(-1)"
      class="absolute left-2 w-10 h-10 bg-white border border-slate-200 text-slate-900 rounded-full text-lg flex items-center justify-center z-10 transition-all shadow-sm sm:bg-white/5 sm:hover:bg-white/10 sm:border-white/5 sm:text-white sm:shadow-none">
      ‹
    </button>
    <button id="modal-btn-next" onclick="changeModalPhoto(1)"
      class="absolute right-2 w-10 h-10 bg-white border border-slate-200 text-slate-900 rounded-full text-lg flex items-center justify-center z-10 transition-all shadow-sm sm:bg-white/5 sm:hover:bg-white/10 sm:border-white/5 sm:text-white sm:shadow-none">
      ›
    </button>

    <img id="modal-main-img" src="" alt="Expanded View" class="max-w-full rounded-xl object-contain max-h-[68vh] shadow-2xl select-none border border-white/5">
  </div>

  {{-- Footer Info --}}
  <div class="w-full max-w-4xl mx-auto bg-white border border-slate-100 rounded-xl p-5 text-slate-800 shadow-lg mb-2 sm:bg-slate-900/95 sm:border-slate-800 sm:text-white sm:shadow-none" onclick="event.stopPropagation()">
     <p id="modal-work-desc" class="text-xs text-slate-600 sm:text-slate-400 leading-relaxed font-normal"></p>
     <p id="modal-work-counter" class="text-[10px] text-emerald-600 sm:text-emerald-400 font-bold mt-3 text-right tracking-wider border-t border-slate-100 sm:border-slate-800 pt-2"></p>
  </div>
</div>

@push('css')
<style>
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
@php
    $groupedArray = [];
    foreach($works->groupBy(function($item) { return $item->title . $item->created_at->format('YMDHis'); }) as $group) {
        $first = $group->first();
        $photos = [];
        foreach($group as $p) {
            $photos[] = asset('storage/' . $p->image_path);
        }
        $groupedArray[] = [
            'title' => $first->title,
            'date' => $first->created_at->diffForHumans(),
            'desc' => $first->description ?? 'No extra data details provided for this project showcase.',
            'photos' => $photos
        ];
    }
@endphp

<script>
const portfolioGroups = @json($groupedArray);

let activeGroupIndex = 0;
let activePhotoIndex = 0;

function scrollCustomerSlider(containerIdx, direction) {
    const slider = document.getElementById(`customer-slider-${containerIdx}`);
    const scrollAmount = slider.clientWidth;
    if (direction === 'left') {
        slider.scrollLeft -= scrollAmount;
    } else {
        slider.scrollLeft += scrollAmount;
    }
}

function openGroupModal(groupIdx, photoIdx) {
    activeGroupIndex = groupIdx;
    activePhotoIndex = photoIdx;
    
    renderModalData();
    
    document.getElementById('custom-lightbox').classList.remove('hidden');
    document.getElementById('custom-lightbox').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeGroupModal(e) {
    if (!e || e.target === document.getElementById('custom-lightbox')) {
        document.getElementById('custom-lightbox').classList.add('hidden');
        document.getElementById('custom-lightbox').classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function changeModalPhoto(direction) {
    const group = portfolioGroups[activeGroupIndex];
    activePhotoIndex = (activePhotoIndex + direction + group.photos.length) % group.photos.length;
    renderModalData();
}

function renderModalData() {
    const group = portfolioGroups[activeGroupIndex];
    
    document.getElementById('modal-main-img').src = group.photos[activePhotoIndex];
    document.getElementById('modal-work-title').textContent = group.title;
    document.getElementById('modal-work-date').textContent = group.date;
    document.getElementById('modal-work-desc').textContent = group.desc;
    document.getElementById('modal-work-counter').textContent = `IMAGE ${activePhotoIndex + 1} OF ${group.photos.length}`;
    
    if(group.photos.length <= 1) {
        document.getElementById('modal-btn-prev').style.display = 'none';
        document.getElementById('modal-btn-next').style.display = 'none';
    } else {
        document.getElementById('modal-btn-prev').style.display = '';
        document.getElementById('modal-btn-next').style.display = '';
    }
}

document.addEventListener('keydown', e => {
    if (document.getElementById('custom-lightbox').classList.contains('hidden')) return;
    if (e.key === 'ArrowLeft')  changeModalPhoto(-1);
    if (e.key === 'ArrowRight') changeModalPhoto(1);
    if (e.key === 'Escape')     closeGroupModal();
});
</script>
@endpush
@endsection