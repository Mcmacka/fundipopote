<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — FundiPopote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-subtle-grid {
            background-size: 30px 30px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0, 0, 0, 0.02) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between bg-subtle-grid">

    <!-- Global Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2 font-bold text-lg tracking-tight text-slate-900">
                <span class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white text-xs font-extrabold shadow-sm">
                    FP
                </span>
                Fundi<span class="text-emerald-600">Popote</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="text-sm font-semibold bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg transition-all shadow-sm">
                    Get Started
                </a>
            </div>
        </div>
    </header>

    <!-- Main Architecture -->
    <main class="max-w-5xl mx-auto px-6 py-20 flex-grow flex flex-col justify-center items-center relative">
        <!-- Accent Glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-100/40 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 text-center max-w-3xl">
            <!-- Badge Notification -->
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-200/60 rounded-full text-xs font-semibold text-emerald-800 mb-6">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                Tanzania's Service Marketplace
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                Connecting Demand with Approved Technical Expertise
            </h1>
            
            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-12 leading-relaxed">
                FundiPopote is a digital ecosystem built to bridge the gap between clients needing technical solutions and verified service providers operating within their immediate geographical vicinity.
            </p>
        </div>

        <!-- Segmentation Matrix -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-4xl mb-12 relative z-10">
            
            <!-- Segment: Consumer -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Service Consumers</div>
                    <h3 class="font-bold text-xl text-slate-900 mb-3">For Clients</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">
                        Source, evaluate, and connect with technical specialists in your area. Review detailed verification data, skill assessments, and authentic history logs without friction.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-400">
                    <span>Zero platform deployment fees</span>
                    <span class="text-emerald-600 font-semibold">Verified Access &rarr;</span>
                </div>
            </div>

            <!-- Segment: Provider -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[10px] uppercase font-bold tracking-widest px-3 py-1 rounded-bl-lg">
                    Monetized
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Service Providers</div>
                    <h3 class="font-bold text-xl text-slate-900 mb-3">For Technicians</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">
                        Establish your digital business footprint. Activating a secure **Subscription Plan** grants full profile placement and high-intent visibility to active clients seeking nearby service contractors.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-400">
                    <span>Subscription-driven discovery</span>
                    <span class="text-emerald-600 font-semibold">Premium Visibility &rarr;</span>
                </div>
            </div>
            
        </div>

        <!-- Primary Action Pipeline -->
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto relative z-10">
            <a href="{{ route('register') }}" 
               class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-center shadow-md shadow-emerald-900/10 transition-all active:scale-[0.98]">
                Establish Account
            </a>
            <a href="{{ route('login') }}" 
               class="px-8 py-3.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-semibold rounded-xl text-center shadow-sm transition-all active:scale-[0.98]">
                Access Dashboard
            </a>
        </div>
    </main>

    <!-- Global Footer -->
    <footer class="bg-white border-t border-slate-200/60 py-6 text-center text-xs font-medium text-slate-400">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>&copy; 2026 FundiPopote Ecosystem. All rights reserved.</div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-600 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-slate-600 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-slate-600 transition-colors">Infrastructure Status</a>
            </div>
        </div>
    </footer>

</body>
</html>