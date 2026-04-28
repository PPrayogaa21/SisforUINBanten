<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 h-16">
    <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:px-8">
        <!-- Left: Mobile menu + Breadcrumb -->
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-600 hover:text-slate-900 transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            @if(!auth()->user()->isAdmin())
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-medium text-emerald-700">{{ ucfirst(session('active_role', 'peserta')) }}</span>
            </div>
            @endif

            <a href="{{ route('landing') }}" class="p-2 text-slate-400 hover:text-slate-600 transition-colors" title="Kembali ke Landing Page">
                <i class="fas fa-home text-lg"></i>
            </a>

            <div class="relative" x-data="{ open: false }">
                <button class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </button>
            </div>
        </div>
    </div>
</header>
