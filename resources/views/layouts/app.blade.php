<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'FundiPopote') — Find Expert Technicians in Tanzania</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  * { font-family: 'Inter', sans-serif; }
  .gradient-hero { background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%); }
  .card-hover { transition: all 0.2s ease; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
  .btn-primary { background: linear-gradient(135deg, #059669, #047857); transition: all 0.2s; }
  .btn-primary:hover { background: linear-gradient(135deg, #047857, #065f46); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(5,150,105,0.4); }
  .nav-glass { background: rgba(255,255,255,0.98); backdrop-filter: blur(10px); }
</style>
@stack('styles')
</head>
<body class="bg-gray-50 min-h-screen antialiased text-gray-900 flex flex-col justify-between">

{{-- HEADER / NAVBAR --}}
<nav class="nav-glass border-b border-gray-100 sticky top-0 z-50 shadow-sm">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="flex justify-between items-center h-16">

      {{-- Kushoto: Hamburger (Simu tu) + Logo --}}
      <div class="flex items-center gap-3">
        @auth
          {{-- Kitufe hiki kinatokea TU browser ikiwa ndogo (md:hidden) --}}
          <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
          </button>
        @endauth

        <a href="/" class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg btn-primary flex items-center justify-center text-white font-bold text-sm">F</div>
          <span class="text-lg font-bold text-gray-900">Fundi<span class="text-emerald-600">Popote</span></span>
        </a>
      </div>

      {{-- KATIKATI: LINKS ZA JUU (Zinaonekana kwenye PC tu - hidden md:flex) --}}
      <div class="hidden md:flex items-center gap-1">
        @auth
          @if(auth()->user()->isCustomer())
            <a href="{{ route('customer.search') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customer.search') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
              Find Technicians
            </a>
            <a href="{{ route('customer.bookings.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customer.bookings*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
              My Bookings
            </a>
            <a href="{{ route('customer.profile.edit') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">My Profile</a>
          @endif

          @if(auth()->user()->isTechnician())
            <a href="{{ route('technician.dashboard') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
              Dashboard
            </a>
            <a href="{{ route('technician.profile.edit') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.profile.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
              My Profile
            </a>
            <a href="{{ route('technician.portfolio.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.portfolio.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
              Portfolio
            </a>
            <a href="{{ route('technician.subscription.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.subscription.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
              Subscription
            </a>
          @endif
        @endauth
      </div>

{{-- KULIA: PROFILE NA SIGN OUT --}}
<div class="flex items-center gap-3">
    @auth
        <div class="flex items-center gap-2">
            @php
                $profile = auth()->user()->isTechnician() 
                    ? auth()->user()->technicianProfile 
                    : auth()->user()->customerProfile;
                $hasPhoto = $profile && $profile->profile_photo;
            @endphp

            @if($hasPhoto)
                <img src="{{ asset('storage/' . $profile->profile_photo) }}" 
                     alt="Profile" 
                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
            @else
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white uppercase"
                     style="background-color: {{ '#' . substr(md5(auth()->user()->name), 0, 6) }};">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif

            {{-- Jina la user litaonekana pembeni ya picha muda wote --}}
            <span class="text-sm font-semibold text-gray-700 truncate max-w-[100px]">
                {{ explode(' ', auth()->user()->name)[0] }}
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="hidden md:block border-l border-gray-200 pl-2 ml-1">
            @csrf
            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition">Sign out</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Sign in</a>
    @endauth
</div>
        

    </div>
  </div>
</nav>

{{-- SEHEMU YA MAUDHUI (PAGES CONTENT) --}}
<div class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6">
  
  {{-- FLASH MESSAGES --}}
  @if(session('success') || session('warning') || session('error') || $errors->any())
  <div class="mt-4">
    @if(session('success'))
      <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
      </div>
    @endif
    @if(session('warning'))
      <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('warning') }}</p>
      </div>
    @endif
    @if(session('error') || $errors->any())
      <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-3">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <div class="text-sm">
          @if(session('error')){{ session('error') }}@endif
          @if($errors->any())
            <ul class="list-disc list-inside space-y-0.5">
              @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
          @endif
        </div>
      </div>
    @endif
  </div>
  @endif

  {{-- MAIN VIEW CONTENT --}}
  <main class="py-6">
    @yield('content')
  </main>
</div>

{{-- SIDEBAR YA SIMU (Inafanya kazi Kwenye Simu/Browser Ndogo Tu - md:hidden) --}}
@auth
  {{-- Sidebar ya Simu - Imeboreshwa na Icons --}}
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 top-0 z-50 w-64 bg-white border-r border-gray-100 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden flex flex-col p-4 shadow-xl">
    <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
      <span class="text-sm font-bold text-gray-900">Fundi<span class="text-emerald-600">Popote</span></span>
      <button onclick="toggleSidebar()" class="text-gray-400 hover:text-gray-900 p-1">X</button>
    </div>

    @if(auth()->user()->isCustomer())
      <a href="{{ route('customer.search') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Find Technicians
      </a>
      <a href="{{ route('customer.bookings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        My Bookings
      </a>
      <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        My Profile
      </a>
    @endif

    @if(auth()->user()->isTechnician())
      <a href="{{ route('technician.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        Dashboard
      </a>
      <a href="{{ route('technician.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        My Profile
      </a>
      <a href="{{ route('technician.portfolio.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
        Portfolio
      </a>
      <a href="{{ route('technician.subscription.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        Subscription
      </a>
    @endif

    <div class="mt-auto border-t border-gray-100 pt-3">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Sign Out
        </button>
      </form>
    </div>
</aside>

  {{-- Backdrop giza la nyuma (Simu tu) --}}
  <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden md:hidden"></div>
@endauth

{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-400 w-full mt-20">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8 text-sm">
      <div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-7 h-7 rounded-lg btn-primary flex items-center justify-center text-white font-bold text-xs">F</div>
          <span class="text-white font-bold">FundiPopote</span>
        </div>
        <p class="leading-relaxed">Tanzania's trusted marketplace for finding skilled local technicians.</p>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-3">Services</h4>
        <ul class="space-y-1.5">
          <li>Electrical Work</li>
          <li>Plumbing</li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-3">Contact</h4>
        <p>info@fundipopote.co.tz</p>
      </div>
    </div>
    <div class="border-t border-gray-800 pt-6 text-center text-xs">
      © {{ date('Y') }} FundiPopote Tanzania. All rights reserved.
    </div>
  </div>
</footer>

{{-- SCRIPT YA KUFUNGUA SIDEBAR KWENYE SIMU PeKEE --}}
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
}
</script>

@stack('scripts')
</body>
</html>