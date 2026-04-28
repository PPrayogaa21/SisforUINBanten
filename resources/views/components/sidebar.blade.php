@php
    $currentRole = session('active_role', 'peserta');
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

<aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-72 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 border-r border-slate-800/50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-slate-800/50">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
            <i class="fas fa-building-columns text-white text-sm"></i>
        </div>
        <div>
            <h2 class="text-white font-bold text-sm tracking-wide">SITSFOR</h2>
            <p class="text-slate-500 text-xs">Kegiatan Luar Kantor</p>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden ml-auto text-slate-400 hover:text-white">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-slate-800/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-semibold text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-slate-500 text-xs truncate">
                    @if($isAdmin)
                        <span class="inline-flex items-center gap-1 text-amber-400"><i class="fas fa-shield-halved text-[10px]"></i> Administrator</span>
                    @else
                        <span class="inline-flex items-center gap-1 text-emerald-400"><i class="fas fa-user text-[10px]"></i> {{ ucfirst($currentRole) }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-4 space-y-1 overflow-y-auto h-[calc(100vh-200px)]">
        @if($isAdmin)
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Admin</p>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.kegiatan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.kegiatan.*') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-calendar-days w-5 text-center"></i>
                <span>Kelola Kegiatan</span>
            </a>

        @else
            @if($currentRole === 'peserta')
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Peserta</p>

                <a href="{{ route('peserta.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('peserta.dashboard') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('peserta.kegiatan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('peserta.kegiatan.*') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span>Kegiatan Saya</span>
                </a>

            @elseif($currentRole === 'narasumber')
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Narasumber</p>

                <a href="{{ route('narasumber.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('narasumber.dashboard') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('narasumber.kegiatan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('narasumber.kegiatan.*') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-chalkboard-user w-5 text-center"></i>
                    <span>Kegiatan Saya</span>
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-slate-800/50">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Lainnya</p>
                <a href="{{ route('select-role') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                    <i class="fas fa-repeat w-5 text-center"></i>
                    <span>Ganti Role</span>
                </a>
            </div>
        @endif
    </nav>

    <!-- Logout -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-800/50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200">
                <i class="fas fa-right-from-bracket w-5 text-center"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
