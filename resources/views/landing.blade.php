<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISFOR — Sistem Informasi Kegiatan UIN SMH Banten</title>
    <meta name="description" content="Sistem Informasi Kegiatan Luar Kantor UIN Sultan Maulana Hasanuddin Banten">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        @keyframes floatSlow { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        #navbar { transition: background .4s ease, backdrop-filter .4s ease, border-color .4s ease, box-shadow .4s ease; }
        #navbar.scrolled { background: rgba(8,14,26,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.07); box-shadow: 0 4px 28px rgba(0,0,0,0.45); }
        .anim-1 { animation: fadeUp .7s ease both; }
        .anim-2 { animation: fadeUp .7s .15s ease both; }
        .anim-3 { animation: fadeUp .7s .3s ease both; }
        .anim-4 { animation: fadeUp .7s .45s ease both; }
        .anim-5 { animation: fadeUp .7s .6s ease both; }
        .scroll-hint { animation: floatSlow 3s ease-in-out infinite; }
        .glass { background:rgba(255,255,255,0.05); backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.09); }
        .nav-pill { transition: all .2s; }
        .nav-pill:hover { color:#fff; }
        #map { height: 450px; }
    </style>
</head>
<body class="font-sans antialiased text-white" style="background-color:#080e1a;">

    <!-- ===== NAVBAR ===== -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shadow-lg">
                        <img src="/img/logo-uin.png" alt="Logo UIN SMH Banten" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="font-extrabold text-sm text-white leading-none">UIN SMH Banten</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">Sistem Informasi Kegiatan</p>
                    </div>
                </div>

                <!-- Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#hero" class="nav-pill text-sm text-slate-400 font-medium">Beranda</a>
                    <a href="#kegiatan" class="nav-pill text-sm text-slate-400 font-medium">Kegiatan</a>
                    @auth
                    <a href="#peta" class="nav-pill text-sm text-slate-400 font-medium">Lokasi</a>
                    @endauth
                </div>

                <!-- Auth -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-lg shadow-emerald-900/40">
                                <i class="fas fa-gauge-high mr-1.5"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-lg shadow-emerald-900/40">
                                <i class="fas fa-gauge-high mr-1.5"></i>Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm text-slate-300 hover:text-white font-semibold transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-lg shadow-emerald-900/40">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">

        <!-- Campus background photo -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/uin2.jpg') }}" class="w-full h-full object-cover object-center" style="opacity:0.5;" alt="Kampus UIN SMH Banten">
            <!-- Layered gradient: dark top and strong bottom fade -->
            <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(8,14,26,0.35) 0%, rgba(8,14,26,0.60) 55%, rgba(8,14,26,1) 100%);"></div>
        </div>

        <!-- Subtle glow orb -->
        <div class="absolute bottom-1/3 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-emerald-700/20 rounded-full blur-[100px] z-10 pointer-events-none"></div>

        <!-- Content -->
        <div class="relative z-20 max-w-4xl mx-auto px-4 text-center pt-28">

            <!-- Institution badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full glass text-slate-300 text-sm font-semibold mb-8 anim-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                UIN Sultan Maulana Hasanuddin Banten
            </div>

            <!-- Main title — clean white, no rainbow -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-tight mb-6 text-white anim-2">
                Sistem Informasi<br>
                <span class="text-emerald-400">Kegiatan Luar Kantor</span>
            </h1>

            <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-12 leading-relaxed anim-3 font-normal">
                Platform terpadu untuk mengelola seluruh kegiatan institusi secara lebih terstruktur, transparan, dan mudah diakses.
            </p>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 anim-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="group px-9 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-base shadow-xl shadow-emerald-900/50 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                            Buka Dashboard <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="group px-9 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-base shadow-xl shadow-emerald-900/50 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                            Buka Dashboard <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="group px-9 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-base shadow-xl shadow-emerald-900/50 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                        Mulai Sekarang <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('login') }}" class="px-9 py-4 rounded-2xl glass hover:bg-white/10 text-white font-bold text-base hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2.5">
                        <i class="fas fa-right-to-bracket"></i> Masuk
                    </a>
                @endauth
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 max-w-xl mx-auto mt-20 anim-5">
                <div class="glass rounded-2xl p-5">
                    <p class="text-3xl font-black text-white">{{ $totalKegiatan }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wide">Total Kegiatan</p>
                </div>
                <div class="glass rounded-2xl p-5">
                    <p class="text-3xl font-black text-white">{{ $totalKegiatanSelesai }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wide">Selesai</p>
                </div>
                <div class="glass rounded-2xl p-5">
                    <p class="text-3xl font-black text-white">{{ $kegiatanBerlangsung->count() }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wide">Berlangsung</p>
                </div>
            </div>

            <!-- Scroll hint -->
            <div class="mt-16 scroll-hint opacity-30">
                <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
            </div>
        </div>
    </section>

    <!-- ===== FITUR STRIP ===== -->
    <section class="py-14 border-y border-white/5" style="background:rgba(255,255,255,0.02);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    ['icon'=>'calendar-check','label'=>'Manajemen Kegiatan','sub'=>'Kelola semua acara institusi'],
                    ['icon'=>'users','label'=>'Data Peserta','sub'=>'Presensi & informasi lengkap'],
                    ['icon'=>'chalkboard-user','label'=>'Narasumber','sub'=>'Profil & materi pemateri'],
                    ['icon'=>'chart-line','label'=>'Evaluasi & Laporan','sub'=>'Kuesioner dan analisis data'],
                ] as $f)
                <div class="p-5 rounded-2xl border border-white/7 hover:border-emerald-600/40 hover:bg-white/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600/15 border border-emerald-600/20 flex items-center justify-center mb-3">
                        <i class="fas fa-{{ $f['icon'] }} text-emerald-400 text-sm"></i>
                    </div>
                    <p class="font-bold text-sm text-white mb-0.5">{{ $f['label'] }}</p>
                    <p class="text-xs text-slate-500 leading-snug">{{ $f['sub'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== KEGIATAN TERBARU ===== -->
    <section id="kegiatan" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-14">
                <p class="text-emerald-400 text-sm font-bold uppercase tracking-widest mb-3">Kegiatan Terbaru</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Kegiatan yang Sedang & Akan<br class="hidden sm:block"> Berlangsung</h2>
                <p class="text-slate-400 mt-3 text-base max-w-xl">Informasi kegiatan terkini yang diselenggarakan oleh UIN SMH Banten</p>
            </div>

            @if($kegiatanTerbaru->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kegiatanTerbaru as $item)
                @php
                    $iconMap = ['seminar'=>'chalkboard-user','rapat'=>'handshake','pelatihan'=>'graduation-cap','workshop'=>'screwdriver-wrench'];
                    $icon = $iconMap[$item->jenis] ?? 'calendar-check';
                    $colorMap = ['seminar'=>'from-slate-700 to-slate-800','rapat'=>'from-slate-700 to-blue-900','pelatihan'=>'from-slate-700 to-slate-900','workshop'=>'from-slate-800 to-emerald-950','lainnya'=>'from-slate-700 to-slate-800'];
                    $cardGrad = $colorMap[$item->jenis] ?? 'from-slate-700 to-slate-800';
                    $statusLabel = ['draft'=>['Draf','bg-slate-600/50 text-slate-300'],'published'=>['Terbuka','bg-blue-500/30 text-blue-300'],'ongoing'=>['Berlangsung','bg-amber-500/30 text-amber-200'],'completed'=>['Selesai','bg-emerald-500/30 text-emerald-300'],'cancelled'=>['Dibatalkan','bg-red-500/30 text-red-300']];
                    $sl = $statusLabel[$item->status] ?? ['Draft','bg-slate-600/50 text-slate-300'];
                @endphp
                <div class="group relative cursor-pointer rounded-2xl overflow-hidden border border-white/8 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/40"
                     style="min-height:300px;"
                     onclick="window.location.href='{{ route('kegiatan.show.public', $item) }}'">

                    <div class="absolute inset-0 bg-gradient-to-br {{ $cardGrad }}"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

                    <!-- Top -->
                    <div class="absolute top-4 left-4 right-4 flex items-start justify-between z-10">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-black/40 backdrop-blur text-slate-200 border border-white/10">
                            <i class="fas fa-{{ $icon }} mr-1.5 opacity-70"></i>{{ ucfirst($item->jenis) }}
                        </span>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold backdrop-blur border border-white/10 {{ $sl[1] }}">{{ $sl[0] }}</span>
                    </div>

                    <!-- Bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
                        <h3 class="text-base font-bold text-white leading-snug line-clamp-2 mb-3 group-hover:text-emerald-300 transition-colors">{{ $item->nama_kegiatan }}</h3>
                        <div class="flex items-center gap-4 text-xs text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-calendar-days text-emerald-500/80"></i>
                                {{ $item->waktu_mulai->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5 min-w-0">
                                <i class="fas fa-location-dot text-emerald-500/80 shrink-0"></i>
                                <span class="truncate">{{ $item->tempat }}</span>
                            </span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-300">
                            <span class="text-xs text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-users text-emerald-500/80"></i> {{ $item->peserta->count() }} Peserta
                            </span>
                            <span class="text-xs font-bold text-emerald-400 flex items-center gap-1">
                                Lihat Detail <i class="fas fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20 rounded-3xl border border-white/5" style="background:rgba(255,255,255,0.02);">
                <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-xmark text-2xl text-slate-600"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada kegiatan yang dipublikasikan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- ===== PETA ===== -->
    @if(count($locations) > 0)
    <section id="peta" class="py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14">
                <p class="text-emerald-400 text-sm font-bold uppercase tracking-widest mb-3">Sebaran Lokasi</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Peta Lokasi Kegiatan</h2>
                <p class="text-slate-400 mt-3 text-base">Sebaran lokasi kegiatan luar kantor UIN SMH Banten</p>
            </div>
            <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                <div id="map" class="w-full"></div>
            </div>
        </div>
    </section>
    @endif

    <!-- ===== FOOTER ===== -->
    <footer class="border-t border-white/5 py-12 mt-4" style="background:rgba(0,0,0,0.3);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center">
                        <img src="/img/logo-uin.png" alt="Logo UIN" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="font-bold text-sm text-white">UIN Sultan Maulana Hasanuddin Banten</p>
                        <p class="text-xs text-slate-500 mt-0.5">Sistem Informasi Kegiatan Luar Kantor</p>
                    </div>
                </div>
                <div class="flex items-center gap-8 text-sm text-slate-500">
                    <a href="#hero" class="hover:text-slate-300 transition-colors">Beranda</a>
                    <a href="#kegiatan" class="hover:text-slate-300 transition-colors">Kegiatan</a>
                    <a href="#peta" class="hover:text-slate-300 transition-colors">Lokasi</a>
                </div>
                <p class="text-slate-600 text-sm">&copy; {{ date('Y') }} UIN SMH Banten. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Navbar smooth scroll effect (CSS transition handles smoothness)
            const nav = document.getElementById('navbar');
            const onScroll = () => {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll(); // run once on load

            // Map
            const locations = @json($locations);
            if (!locations || !locations.length) return;

            const map = L.map('map').setView([locations[0].lat, locations[0].lng], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap &copy; CARTO'
            }).addTo(map);

            const icon = L.divIcon({
                html: `<div style="width:14px;height:14px;background:#10b981;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 3px rgba(16,185,129,0.3);"></div>`,
                className: '',
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });

            const bounds = [];
            locations.forEach(loc => {
                const m = L.marker([loc.lat, loc.lng], {icon}).addTo(map);
                bounds.push([loc.lat, loc.lng]);
                m.bindPopup(`<div style="font-family:Inter,sans-serif;padding:4px;"><b style="color:#0f172a;font-size:13px;">${loc.nama}</b><br><small style="color:#64748b;">${loc.tempat} — ${loc.waktu}</small></div>`);
            });
            if (bounds.length > 1) map.fitBounds(bounds, { padding: [50, 50] });
        });
    </script>
</body>
</html>
