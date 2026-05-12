@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $currentRole = 'User Biasa';
    if (request()->routeIs('peserta.*')) {
        $currentRole = 'Peserta';
    } elseif (request()->routeIs('narasumber.*')) {
        $currentRole = 'Narasumber';
    }
@endphp

<aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-72 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 border-r border-slate-800/50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-slate-800/50">
        <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-lg shadow-emerald-500/20">
            <img src="/img/logo-uin.png" alt="Logo UIN" class="w-full h-full object-contain">
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
            <div class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                @if(auth()->user()->biodata && auth()->user()->biodata->foto)
                    <img src="{{ asset('storage/' . auth()->user()->biodata->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->biodata->nama_lengkap ?? auth()->user()->username ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->biodata->nama_lengkap ?? auth()->user()->username ?? 'User' }}</p>
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

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.create') || request()->routeIs('admin.users.edit') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-users-gear w-5 text-center"></i>
                <span>Manajemen User</span>
            </a>

            <a href="{{ route('admin.users.approval') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.users.approval') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-user-check w-5 text-center"></i>
                <span>Approval User</span>
                @php
                    $pendingCount = \App\Models\User::where('account_status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

        @else
            <div class="mt-4">

            <p class="text-xs text-slate-400 px-3 mb-2">Menu</p>

            {{-- DASHBOARD UTAMA --}}
            <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <i class="fas fa-house w-5 text-center"></i>
                    <span>Dashboard</span>
            </a>

            {{-- DASHBOARD PESERTA --}}
            @if(request()->routeIs('peserta.*'))
            <a href="{{ route('peserta.dashboard') }}" 
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('peserta.*') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-user-check w-5 text-center"></i>
                <span>Dashboard Peserta</span>
            </a>
            @endif

            {{-- DASHBOARD NARASUMBER --}}
            @if(request()->routeIs('narasumber.*'))
            <a href="{{ route('narasumber.dashboard') }}" 
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('narasumber.*') ? 'bg-emerald-500/10 text-emerald-400 font-medium' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <i class="fas fa-chalkboard-user w-5 text-center"></i>
                <span>Dashboard Narasumber</span>
            </a>
            @endif
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
