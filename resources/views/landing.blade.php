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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-white">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-white/5" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                        <i class="fas fa-building-columns text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-lg">SITSFOR</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#hero" class="text-sm text-slate-400 hover:text-white transition-colors">Beranda</a>
                    <a href="#kegiatan" class="text-sm text-slate-400 hover:text-white transition-colors">Kegiatan</a>
                    <a href="#dokumentasi" class="text-sm text-slate-400 hover:text-white transition-colors">Dokumentasi</a>
                    <a href="#peta" class="text-sm text-slate-400 hover:text-white transition-colors">Lokasi</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('select-role') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">Dashboard</a>
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
                    <a href="{{ route('select-role') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-lg shadow-2xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-1 transition-all duration-300">
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
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-20 animate-fade-in" style="animation-delay:0.4s">
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
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-blue-400">{{ $dokumentasi->count() }}</p>
                    <p class="text-sm text-slate-400 mt-1">Dokumentasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kegiatan Terbaru -->
    <section id="kegiatan" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm mb-4">Kegiatan</span>
                <h2 class="text-3xl sm:text-4xl font-bold">Kegiatan <span class="text-emerald-400">Terbaru</span></h2>
                <p class="text-slate-400 mt-3 max-w-xl mx-auto">Daftar kegiatan luar kantor terbaru yang diselenggarakan oleh institusi</p>
            </div>

            @if($kegiatanTerbaru->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kegiatanTerbaru as $item)
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-emerald-500/30 hover:bg-white/[0.07] transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->jenis_badge }}">{{ ucfirst($item->jenis) }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors mb-2">{{ $item->nama_kegiatan }}</h3>
                    <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <div class="space-y-2 text-sm text-slate-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-days w-4 text-center text-emerald-500/70"></i>
                            <span>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-location-dot w-4 text-center text-emerald-500/70"></i>
                            <span>{{ $item->tempat }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <i class="fas fa-calendar-xmark text-4xl text-slate-700 mb-4"></i>
                <p class="text-slate-500">Belum ada kegiatan yang dipublikasikan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Dokumentasi -->
    <section id="dokumentasi" class="py-24 relative bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-sm mb-4">Galeri</span>
                <h2 class="text-3xl sm:text-4xl font-bold">Dokumentasi <span class="text-teal-400">Kegiatan</span></h2>
                <p class="text-slate-400 mt-3 max-w-xl mx-auto">Momen-momen penting dari kegiatan yang telah dilaksanakan</p>
            </div>

            @if($dokumentasi->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($dokumentasi as $dok)
                <div class="group relative aspect-square rounded-xl overflow-hidden cursor-pointer">
                    <img src="{{ asset('storage/' . $dok->file_path) }}" alt="{{ $dok->caption ?? 'Dokumentasi' }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-sm font-medium">{{ $dok->caption ?? 'Dokumentasi Kegiatan' }}</p>
                            @if($dok->kegiatan)
                            <p class="text-slate-300 text-xs mt-1">{{ $dok->kegiatan->nama_kegiatan }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <i class="fas fa-images text-4xl text-slate-700 mb-4"></i>
                <p class="text-slate-500">Belum ada dokumentasi kegiatan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Peta Lokasi -->
    <section id="peta" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-sm mb-4">Lokasi</span>
                <h2 class="text-3xl sm:text-4xl font-bold">Peta Lokasi <span class="text-cyan-400">Kegiatan</span></h2>
                <p class="text-slate-400 mt-3 max-w-xl mx-auto">Lokasi kegiatan luar kantor pada peta interaktif</p>
            </div>

            <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5 aspect-[16/9] relative" id="map-container">
                <div id="map" class="w-full h-full min-h-[400px]"></div>
                @if($kegiatanDenganLokasi->count() === 0)
                <div class="absolute inset-0 flex items-center justify-center bg-slate-800/50">
                    <div class="text-center">
                        <i class="fas fa-map-location-dot text-4xl text-slate-600 mb-3"></i>
                        <p class="text-slate-400">Belum ada lokasi kegiatan</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                        <i class="fas fa-building-columns text-white text-xs"></i>
                    </div>
                    <span class="font-bold">SITSFOR Kegiatan</span>
                </div>
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} Sistem Informasi Kegiatan Luar Kantor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @if(env('GOOGLE_MAPS_API_KEY'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
    @endif
    <script>
        function initMap() {
            
        const locations = @json($locations);const locations = @json($locations);

            if (!locations.length) return;

            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: locations[0],
                styles: [{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#0e1626"}]}]
            });

            locations.forEach(loc => {
                const marker = new google.maps.Marker({ position: { lat: loc.lat, lng: loc.lng }, map });
                const infoWindow = new google.maps.InfoWindow({
                    content: `<div style="color:#333;padding:4px"><strong>${loc.nama}</strong><br><small>${loc.tempat} - ${loc.waktu}</small></div>`
                });
                marker.addListener('click', () => infoWindow.open(map, marker));
            });
        }

        // Fallback: show placeholder if no Google Maps API key
        if (!document.querySelector('script[src*="maps.googleapis"]')) {
            const mapDiv = document.getElementById('map');
            if (mapDiv) {
                mapDiv.innerHTML = '<div class="w-full h-full flex items-center justify-center bg-slate-800/80"><div class="text-center"><i class="fas fa-map-location-dot text-5xl text-emerald-500/30 mb-4"></i><p class="text-slate-400 text-sm">Konfigurasi Google Maps API Key<br>di file .env untuk menampilkan peta</p></div></div>';
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 100) { nav.classList.add('shadow-xl', 'shadow-black/10'); }
            else { nav.classList.remove('shadow-xl', 'shadow-black/10'); }
        });
    </script>
</body>
</html>
