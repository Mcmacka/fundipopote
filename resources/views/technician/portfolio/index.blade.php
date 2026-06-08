@extends('layouts.technician')
@section('title', 'My Portfolio')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-bold text-slate-900">My Portfolio</h1>
            <p class="text-sm text-slate-400 mt-1">Manage the work photos displayed on your profile</p>
        </div>
        <a href="{{ route('technician.portfolio.create') }}" 
           class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Upload New Work
        </a>
    </div>

    {{-- Alert Success Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Portfolio Grid --}}
    @if($works->isEmpty())
        <div class="text-center py-16 bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-700">No photos uploaded yet</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Upload pictures of your complete projects to build trust with customers.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            @foreach($works->groupBy(function($item) { return $item->title . $item->created_at->format('YMDHis'); }) as $group)
                @php 
                    $firstWork = $group->first(); 
                @endphp
                
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
                    
                    {{-- Header ya Slider (Vifungo vimebaki seti moja tu vinavyolenga TITLE) --}}
                    <div class="p-4 flex items-center justify-between border-b border-slate-50">
                        <div class="pr-2 truncate">
                            <h3 class="text-sm font-bold text-slate-800 truncate">{{ $firstWork->title }}</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $firstWork->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            {{-- Visible toggle inayolenga $firstWork->title --}}
                            <form action="{{ route('technician.portfolio.toggle', $firstWork->title) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg border transition text-xs font-medium {{ $firstWork->is_visible ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $firstWork->is_visible ? 'Visible' : 'Hidden' }}
                                </button>
                            </form>
                            
                            {{-- Futa inayolenga $firstWork->title (Inafuta picha zote za hili kontena) --}}
                            <form action="{{ route('technician.portfolio.destroy', $firstWork->title) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entire project container with ALL its photos?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Instagram Slider Area --}}
                    <div class="relative group">
                        <div id="slider-{{ $firstWork->id }}" 
                             class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none scroll-smooth h-64 bg-slate-900">
                            
                            @foreach($group as $photo)
                                <div class="min-w-full h-full flex-shrink-0 snap-start relative">
                                    <img src="{{ asset('storage/' . $photo->image_path) }}" 
                                         alt="Work Photo" 
                                         class="w-full h-full object-cover select-none pointer-events-none">
                                    
                                    @if($group->count() > 1)
                                        <div class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-medium px-2 py-1 rounded-full backdrop-blur-sm z-10">
                                            {{ $loop->iteration }}/{{ $group->count() }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($group->count() > 1)
                            <button onclick="scrollSlider('slider-{{ $firstWork->id }}', 'left')" 
                                    class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 hover:bg-white text-slate-800 rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition backdrop-blur-sm z-20 hidden sm:flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button onclick="scrollSlider('slider-{{ $firstWork->id }}', 'right')" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 hover:bg-white text-slate-800 rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition backdrop-blur-sm z-20 hidden sm:flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($firstWork->description)
                        <div class="p-4 border-t border-slate-50 bg-slate-50/50">
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                                {{ $firstWork->description }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    @endif
</div>
@endsection

@push('css')
<style>
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
<script>
function scrollSlider(sliderId, direction) {
    const slider = document.getElementById(sliderId);
    const scrollAmount = slider.clientWidth;
    if (direction === 'left') {
        slider.scrollLeft -= scrollAmount;
    } else {
        slider.scrollLeft += scrollAmount;
    }
}
</script>
@endpush