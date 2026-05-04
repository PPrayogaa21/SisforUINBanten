<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITSFOR - Sistem Informasi Kegiatan Luar Kantor</title>
    <meta name="description" content="Sistem Informasi Kegiatan Luar Kantor untuk pengelolaan rapat, seminar, pelatihan, dan kegiatan institusi lainnya">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-white">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-white/5" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white p-1 flex items-center justify-center">
                        <img src="/img/logo-uin.png" alt="Logo UIN" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-lg">SISFOR</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#hero" class="text-sm text-slate-400 hover:text-white transition-colors">Beranda</a>
                    <a href="#kegiatan" class="text-sm text-slate-400 hover:text-white transition-colors">Kegiatan</a>
                    <a href="#peta" class="text-sm text-slate-400 hover:text-white transition-colors">Lokasi</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm text-slate-300 hover:text-white transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-[128px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-500/20 rounded-full blur-[128px] animate-pulse" style="animation-delay:2s"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-cyan-500/5 rounded-full blur-[128px]"></div>
        </div>
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="relative max-w-5xl mx-auto px-4 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm mb-8 animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem Informasi Kegiatan Luar Kantor
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight mb-6 animate-fade-in" style="animation-delay:0.1s">
                Kelola Kegiatan<br>
                <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 bg-clip-text text-transparent">Lebih Efisien</span>
            </h1>

            <p class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 animate-fade-in" style="animation-delay:0.2s">
                Platform digital untuk mengelola rapat, seminar, pelatihan, dan kegiatan institusi lainnya secara terpusat dan terorganisir.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style="animation-delay:0.3s">
                @auth
                    <a href="{{route('dashboard')}}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-lg shadow-2xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-1 transition-all duration-300">
                        Masuk Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-lg shadow-2xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-1 transition-all duration-300">
                        Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl border border-white/10 text-white font-semibold text-lg hover:bg-white/5 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-right-to-bracket mr-2"></i> Masuk
                    </a>
                @endauth
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-20 animate-fade-in" style="animation-delay:0.4s">
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-emerald-400">{{ $totalKegiatan }}</p>
                    <p class="text-sm text-slate-400 mt-1">Total Kegiatan</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-teal-400">{{ $totalKegiatanSelesai }}</p>
                    <p class="text-sm text-slate-400 mt-1">Kegiatan Selesai</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-cyan-400">{{ $kegiatanBerlangsung->count() }}</p>
                    <p class="text-sm text-slate-400 mt-1">Sedang Berlangsung</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kegiatan Terbaru -->
    <section id="kegiatan" class="py-24 relative overflow-hidden">
        {{-- Background glow --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm mb-4 tracking-wide">✦ Kegiatan</span>
                <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Kegiatan <span class="bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Terbaru</span></h2>
                <p class="text-slate-400 mt-4 max-w-lg mx-auto leading-relaxed">Klik kartu untuk melihat informasi lengkap kegiatan yang diselenggarakan</p>
            </div>

            @if($kegiatanTerbaru->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kegiatanTerbaru as $item)
                @php
                    $cover = $item->dokumentasi->first();
                    $iconMap = ['seminar'=>'chalkboard-user','rapat'=>'handshake','pelatihan'=>'graduation-cap','workshop'=>'screwdriver-wrench'];
                    $icon = $iconMap[$item->jenis] ?? 'calendar-check';
                    $colorMap = ['seminar'=>'from-indigo-600 to-purple-700','rapat'=>'from-blue-600 to-cyan-700','pelatihan'=>'from-amber-600 to-orange-700','workshop'=>'from-teal-600 to-emerald-700','lainnya'=>'from-slate-600 to-slate-700'];
                    $cardGrad = $colorMap[$item->jenis] ?? 'from-slate-600 to-slate-700';
                @endphp
                <div class="group relative cursor-pointer rounded-2xl overflow-hidden shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-2 transition-all duration-500 border border-white/5 hover:border-emerald-500/30"
                     style="min-height: 320px;"
                     onclick="window.location.href='{{ route('kegiatan.show.public', $item) }}'">

                    {{-- Background: photo or gradient --}}
                    @if($cover)
                        <img src="{{ asset('storage/' . $cover->file_path) }}"
                             alt="{{ $item->nama_kegiatan }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br {{ $cardGrad }}">
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px); background-size:28px 28px;"></div>
                        </div>
                    @endif

                    {{-- Dark gradient overlay from bottom --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/10"></div>

                    {{-- Top badges --}}
                    <div class="absolute top-4 left-4 right-4 flex items-start justify-between">
                        <div class="flex gap-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-black/40 backdrop-blur-md border border-white/10 text-white">
                                <i class="fas fa-{{ $icon }} mr-1"></i>{{ ucfirst($item->jenis) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($item->dokumentasi->count() > 0)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-black/40 backdrop-blur-md border border-white/10 text-white">
                                <i class="fas fa-images mr-1 text-emerald-400"></i>{{ $item->dokumentasi->count() }}
                            </span>
                            @endif
                            @php
                                $statusColor = ['draft'=>'bg-slate-500/30 text-slate-300','published'=>'bg-blue-500/30 text-blue-300','ongoing'=>'bg-amber-500/30 text-amber-300','completed'=>'bg-emerald-500/30 text-emerald-300','cancelled'=>'bg-red-500/30 text-red-300'];
                                $sc = $statusColor[$item->status] ?? 'bg-slate-500/30 text-slate-300';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-md border border-white/10 {{ $sc }}">{{ ucfirst($item->status) }}</span>
                        </div>
                    </div>

                    {{-- No-photo icon center --}}
                    @if(!$cover)
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-{{ $icon }} text-7xl text-white/10"></i>
                    </div>
                    @endif

                    {{-- Bottom content --}}
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-base sm:text-lg font-bold text-white leading-snug line-clamp-2 mb-3 group-hover:text-emerald-300 transition-colors">{{ $item->nama_kegiatan }}</h3>
                        <div class="flex items-center gap-4 text-xs text-slate-300">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-calendar-days text-emerald-400/80"></i>
                                {{ $item->waktu_mulai->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5 min-w-0">
                                <i class="fas fa-location-dot text-emerald-400/80 flex-shrink-0"></i>
                                <span class="truncate">{{ $item->tempat }}</span>
                            </span>
                        </div>
                        {{-- Hover reveal bar --}}
                        <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                            <span class="flex items-center gap-1.5 text-xs text-slate-300">
                                <i class="fas fa-users text-emerald-400/80"></i> {{ $item->peserta->count() }} Peserta
                            </span>
                            <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-400">
                                Lihat Detail <i class="fas fa-arrow-right text-xs ml-1 group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-20 h-20 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-calendar-xmark text-3xl text-slate-600"></i>
                </div>
                <p class="text-slate-500">Belum ada kegiatan yang dipublikasikan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Peta Lokasi -->
    @if(count($locations) > 0)
    <section id="peta" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-sm mb-4">Lokasi</span>
                <h2 class="text-3xl sm:text-4xl font-bold">Peta Lokasi <span class="text-cyan-400">Kegiatan</span></h2>
                <p class="text-slate-400 mt-3 max-w-xl mx-auto">Lokasi kegiatan luar kantor pada peta interaktif</p>
            </div>

            <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 aspect-[16/9] relative" id="map-container">
                <div id="map" class="w-full h-full min-h-[400px]"></div>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="border-t border-white/5 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center">
                        <img src="/img/logo-uin.png" alt="Logo UIN" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold">SITSFOR Kegiatan</span>
                </div>
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} Sistem Informasi Kegiatan Luar Kantor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locations = @json($locations);

            if (!locations || !locations.length) return;

            const map = L.map('map').setView([locations[0].lat, locations[0].lng], 12);

            // Use standard map tiles to match the dashboard map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const bounds = [];
            locations.forEach(loc => {
                const marker = L.marker([loc.lat, loc.lng]).addTo(map);
                bounds.push([loc.lat, loc.lng]);
                
                const popupContent = `
                    <div style="color:#1e293b;padding:4px;font-family:Inter,sans-serif">
                        <strong style="display:block;margin-bottom:4px">${loc.nama}</strong>
                        <small style="color:#64748b">${loc.tempat} - ${loc.waktu}</small>
                    </div>`;
                marker.bindPopup(popupContent);
            });
            
            if (bounds.length > 1) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 100) { nav.classList.add('shadow-xl', 'shadow-black/10'); }
            else { nav.classList.remove('shadow-xl', 'shadow-black/10'); }
        });
    </script>
</body>
</html>
