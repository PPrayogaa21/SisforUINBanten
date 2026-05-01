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

            <div class="relative" id="profileDropdownContainer">
                <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-semibold overflow-hidden">
                        @if(auth()->user()->biodata && auth()->user()->biodata->foto)
                            <img src="{{ asset('storage/' . auth()->user()->biodata->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden z-50 opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out origin-top-right">
                    
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->nama ?? auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->biodata->nip ?? auth()->user()->username ?? '-' }}</p>
                    </div>

                    <div class="p-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <i class="fas fa-user-edit w-4 text-center"></i> Edit Profil
                        </a>
                        <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <i class="fas fa-key w-4 text-center"></i> Change Password
                        </a>
                    </div>
                    
                    <div class="p-2 border-t border-slate-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown.classList.contains('opacity-0')) {
            dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            dropdown.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
        } else {
            dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            dropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
        }
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('profileDropdownContainer');
        const dropdown = document.getElementById('profileDropdown');
        if (container && !container.contains(event.target)) {
            dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            dropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
        }
    });
</script>
@endpush
