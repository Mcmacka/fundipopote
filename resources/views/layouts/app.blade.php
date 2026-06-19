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

      {{-- Kushoto --}}
      <div class="flex items-center gap-3">
        @auth
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

      {{-- KATIKATI: Links za Desktop --}}
      <div class="hidden md:flex items-center gap-1">
        @auth
          @if(auth()->user()->isCustomer())
            <a href="{{ route('customer.search') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customer.search') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">Find Technicians</a>
            <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customer.bookings*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">My Bookings</a>
            <a href="{{ route('customer.profile.edit') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">My Profile</a>
          @endif
          @if(auth()->user()->isTechnician())
            <a href="{{ route('technician.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
            <a href="{{ route('technician.profile.edit') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.profile.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">My Profile</a>
            <a href="{{ route('technician.portfolio.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.portfolio.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">Portfolio</a>
            <a href="{{ route('technician.subscription.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('technician.subscription.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">Subscription</a>
          @endif
        @endauth
      </div>

      {{-- KULIA: Notifications + Profile --}}
      <div class="flex items-center gap-3">
        @auth
            <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400 hover:text-emerald-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </a>

            <div class="flex items-center gap-2 border-l border-gray-200 pl-3">
                <span class="text-sm font-semibold text-gray-700">{{ explode(' ', auth()->user()->name)[0] }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                @csrf
                <button type="submit" class="text-xs text-gray-400 hover:text-red-500">Sign out</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Sign in</a>
        @endauth
      </div>
    </div>
  </div>
</nav>

{{-- CONTENT --}}
<div class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6">
  <main class="py-6">@yield('content')</main>
</div>

{{-- SIDEBAR YA SIMU (Imekamilika na Icons) --}}
@auth
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 top-0 z-50 w-64 bg-white border-r transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col p-4 shadow-xl">
    <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
      <span class="text-sm font-bold text-gray-900">Fundi<span class="text-emerald-600">Popote</span></span>
      <button onclick="toggleSidebar()" class="text-gray-400 hover:text-gray-900 p-1">X</button>
    </div>

    {{-- CUSTOMER LINKS --}}
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

    {{-- TECHNICIAN LINKS --}}
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

    {{-- NOTIFICATIONS --}}
    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-emerald-600 hover:bg-emerald-50">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
      Notifications
    </a>
    
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
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden md:hidden"></div>
@endauth
@stack('scripts')
<script>
    function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    // Angalia kama vitu hivi vipo kwanza
    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        console.log("Sidebar toggled!"); // Ukiona hii kwenye console, basi JS inafanya kazi
    } else {
        console.error("Sidebar au Overlay haijapatikana! Angalia ID zako.");
    }
}
    function grabGPS() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementById('lat-f').value = position.coords.latitude;
                document.getElementById('lng-f').value = position.coords.longitude;
                alert("Location captured!");
            });
        }
    }
</script>

</body>
</html>