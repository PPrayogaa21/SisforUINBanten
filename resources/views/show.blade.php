<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kegiatan->nama_kegiatan }} - Detail Kegiatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-300 font-sans antialiased min-h-screen relative overflow-x-hidden flex flex-col">

    <!-- Background Glows -->
    <div class="fixed top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-emerald-600/10 blur-[120px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full bg-blue-600/10 blur-[120px] pointer-events-none"></div>

    <!-- Topbar -->
    <nav class="sticky top-0 z-40 bg-slate-950/60 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="group flex items-center gap-2 text-slate-400 hover:text-emerald-400 transition-colors font-medium text-sm">
                <div class="w-8 h-8 rounded-full bg-white/5 group-hover:bg-emerald-500/10 flex items-center justify-center transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </div>
                Kembali ke Beranda
            </a>
            
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <img src="/img/logo-uin.png" alt="Logo UIN" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-white hidden sm:block">SITSFOR</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full relative z-10">
        
        <div class="w-full relative">
            
            <!-- Header Placeholder (No Image) -->
            <div class="w-full h-[30vh] sm:h-[35vh] relative bg-gradient-to-br from-emerald-900/30 to-slate-900">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-20"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            </div>

            <!-- Content Area -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 pb-20 relative -mt-20 sm:-mt-24 z-10">
                
                <!-- Badges -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <div class="flex gap-3">
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-calendar-check mr-1.5"></i> {{ ucfirst($kegiatan->jenis) }}
                        </span>
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 text-slate-200 border border-white/10 backdrop-blur-md">
                            {{ ucfirst($kegiatan->status) }}
                        </span>
                    </div>

                    @auth
                        @php
                            $user = auth()->user();
                            $isPeserta = $kegiatan->peserta()->where('user_id', $user->id)->exists();
                        @endphp
                        @if($user->role === 'peserta' && in_array($kegiatan->status, ['published', 'ongoing']))
                            @if($isPeserta)
                                <a href="{{ route('peserta.kegiatan.show', $kegiatan->id) }}" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg">Lihat Dashboard Kegiatan</a>
                            @else
                                @if($user->biodata_verified)
                                    <form action="{{ route('kegiatan.join', $kegiatan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg">Join Kegiatan</button>
                                    </form>
                                @else
                                    <button onclick="document.getElementById('biodata-modal').classList.remove('hidden')" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg">Join Kegiatan</button>
                                @endif
                            @endif
                        @endif
                    @endauth
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight mb-10 drop-shadow-lg">
                    {{ $kegiatan->nama_kegiatan }}
                </h1>

                <!-- Metadata Row -->
                <div class="grid sm:grid-cols-3 gap-4 sm:gap-6 mb-12">
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 flex items-center sm:items-start gap-4 hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0">
                            <i class="fas fa-calendar-days text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Tanggal</p>
                            <p class="font-medium text-slate-100 text-sm">
                                {{ $kegiatan->waktu_mulai ? $kegiatan->waktu_mulai->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 flex items-center sm:items-start gap-4 hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 flex-shrink-0">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Waktu</p>
                            <p class="font-medium text-slate-100 text-sm">
                                {{ $kegiatan->waktu_mulai ? $kegiatan->waktu_mulai->format('H:i') : '-' }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 flex items-center sm:items-start gap-4 hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <i class="fas fa-location-dot text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Lokasi</p>
                            <p class="font-medium text-slate-100 text-sm line-clamp-2" title="{{ $kegiatan->tempat }}">{{ $kegiatan->tempat ?? '-' }}</p>
                            @if($kegiatan->link_maps)
                                <a href="{{ $kegiatan->link_maps }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20">
                                    <i class="fas fa-map"></i> Buka Google Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if($kegiatan->alamat_lengkap)
                <div class="mb-12 p-6 rounded-2xl bg-white/5 border border-white/5">
                    <h4 class="text-sm font-bold text-slate-300 mb-2 flex items-center gap-2">
                        <i class="fas fa-map-pin text-emerald-400"></i> Alamat Lengkap
                    </h4>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        {{ $kegiatan->alamat_lengkap }}
                    </p>
                </div>
                @endif

                <!-- Divider -->
                <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-12"></div>

                <!-- Description -->
                <article class="mb-16">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-align-left text-sm"></i>
                        </div>
                        Deskripsi Kegiatan
                    </h3>
                    <div class="text-slate-300 leading-relaxed text-lg whitespace-pre-line">
                        {{ $kegiatan->deskripsi ?? 'Belum ada deskripsi yang ditambahkan untuk kegiatan ini.' }}
                    </div>
                </article>

                <!-- Gallery -->
                @if($kegiatan->dokumentasi && $kegiatan->dokumentasi->count() > 0)
                @endif

            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer class="border-t border-white/5 py-8 mt-auto z-10 bg-slate-950/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} Sistem Informasi Kegiatan Luar Kantor. All rights reserved.
        </div>
    </footer>

    @auth
    @if(auth()->user()->role === 'peserta' && !auth()->user()->biodata_verified)
    <!-- Modal Biodata Belum Lengkap -->
    <div id="biodata-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 max-w-md w-full shadow-2xl relative">
            <button onclick="document.getElementById('biodata-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
            <div class="w-12 h-12 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center mb-4 mx-auto text-xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-bold text-center text-white mb-2">Biodata Belum Lengkap</h3>
            <p class="text-slate-400 text-sm text-center mb-6">Anda harus melengkapi profil dan biodata Anda terlebih dahulu sebelum dapat bergabung dengan kegiatan ini.</p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('biodata.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all">Lengkapi Biodata</a>
                <button onclick="document.getElementById('biodata-modal').classList.add('hidden')" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-all">Batal</button>
            </div>
        </div>
    </div>
    @endif
    @endauth

</body>
</html>