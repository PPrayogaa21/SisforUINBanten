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
            
            @php $cover = $kegiatan->dokumentasi->first(); @endphp
            
            <!-- Cover Section -->
            @if($cover)
            <div class="w-full relative h-[250px] sm:h-[300px] md:h-[350px] lg:h-[400px] overflow-hidden">
                <img src="{{ asset('storage/'.$cover->file_path) }}" class="w-full h-full object-cover object-center" alt="Cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent pointer-events-none"></div>
            </div>
            @else
            <div class="w-full h-[40vh] relative bg-gradient-to-br from-emerald-900/30 to-slate-900">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-20"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            </div>
            @endif

            <!-- Content Area -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 pb-20 relative {{ $cover ? '-mt-32 sm:-mt-48' : '-mt-24 sm:-mt-32' }} z-10">
                
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-calendar-check mr-1.5"></i> {{ ucfirst($kegiatan->jenis) }}
                    </span>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 text-slate-200 border border-white/10 backdrop-blur-md">
                        {{ ucfirst($kegiatan->status) }}
                    </span>
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
                        </div>
                    </div>
                </div>

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
                <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-12"></div>
                <section>
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400">
                                <i class="fas fa-images text-sm"></i>
                            </div>
                            Galeri Dokumentasi
                        </h3>
                        <p class="text-slate-400 mt-2 ml-11 text-sm">Momen yang tertangkap pada acara ini.</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-5">
                        @foreach($kegiatan->dokumentasi as $idx => $dok)
                        <div class="group relative cursor-pointer overflow-hidden rounded-2xl bg-slate-800 border border-white/5 hover:border-emerald-500/50 transition-all duration-300 shadow-lg aspect-square"
                             onclick="openLightbox({{ $idx }})">
                            <img 
                                src="{{ asset('storage/' . $dok->file_path) }}"
                                alt="Dokumentasi {{ $loop->iteration }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                            >
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/20 flex items-center justify-center scale-50 group-hover:scale-100 transition-transform duration-500 shadow-xl">
                                    <i class="fas fa-expand text-white"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>
        </div>
    </main>

    <!-- Lightbox Modal -->
    @if($kegiatan->dokumentasi && $kegiatan->dokumentasi->count() > 0)
    <div id="lightbox" class="fixed inset-0 z-[9999] bg-slate-950/95 backdrop-blur-xl hidden items-center justify-center p-4 transition-opacity duration-300 opacity-0" onclick="closeLightboxOnBg(event)">
        <div class="relative w-full max-w-6xl flex flex-col items-center">
            
            <button onclick="closeLightbox()" class="absolute top-4 right-4 md:-top-12 md:right-0 w-12 h-12 rounded-full bg-white/10 hover:bg-red-500 text-white flex items-center justify-center transition-all z-50">
                <i class="fas fa-xmark text-xl"></i>
            </button>

            <button onclick="prevPhoto(event)" class="absolute left-2 md:-left-16 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-50 backdrop-blur-sm" id="lb-prev">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>

            <div class="relative w-full flex justify-center items-center h-[70vh] md:h-[80vh]">
                <img id="lb-image" src="" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transition-all duration-300 scale-95">
            </div>

            <button onclick="nextPhoto(event)" class="absolute right-2 md:-right-16 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-50 backdrop-blur-sm" id="lb-next">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            <div class="mt-6 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-white text-sm font-bold tracking-widest shadow-lg">
                <span id="lb-counter" class="text-emerald-400">1</span> <span class="text-slate-500 mx-1">/</span> {{ $kegiatan->dokumentasi->count() }}
            </div>
        </div>
    </div>

    <script>
        const photos = @json($kegiatan->dokumentasi->map(function($dok) {
            return asset('storage/' . $dok->file_path);
        })->values());
        
        let currentIdx = 0;
        const lightbox = document.getElementById('lightbox');
        const lbImage = document.getElementById('lb-image');
        const lbCounter = document.getElementById('lb-counter');
        const lbPrev = document.getElementById('lb-prev');
        const lbNext = document.getElementById('lb-next');

        function openLightbox(idx) {
            if (!photos || photos.length === 0) return;
            currentIdx = idx;
            updateLightbox();
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            void lightbox.offsetWidth; // trigger reflow
            lightbox.classList.remove('opacity-0');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.add('opacity-0');
            setTimeout(() => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        function closeLightboxOnBg(e) {
            if (e.target === lightbox) closeLightbox();
        }

        function updateLightbox() {
            lbImage.classList.add('scale-95', 'opacity-50');
            setTimeout(() => {
                lbImage.src = photos[currentIdx];
                lbCounter.textContent = currentIdx + 1;
                
                lbPrev.style.opacity = currentIdx === 0 ? '0.3' : '1';
                lbPrev.style.pointerEvents = currentIdx === 0 ? 'none' : 'auto';
                
                lbNext.style.opacity = currentIdx === photos.length - 1 ? '0.3' : '1';
                lbNext.style.pointerEvents = currentIdx === photos.length - 1 ? 'none' : 'auto';
                
                lbImage.classList.remove('scale-95', 'opacity-50');
                lbImage.classList.add('scale-100', 'opacity-100');
            }, 150);
        }

        function prevPhoto(e) {
            if (e) e.stopPropagation();
            if (currentIdx > 0) { currentIdx--; updateLightbox(); }
        }

        function nextPhoto(e) {
            if (e) e.stopPropagation();
            if (currentIdx < photos.length - 1) { currentIdx++; updateLightbox(); }
        }

        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') prevPhoto();
                if (e.key === 'ArrowRight') nextPhoto();
            }
        });
    </script>
    @endif

    <!-- Footer -->
    <footer class="border-t border-white/5 py-8 mt-auto z-10 bg-slate-950/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} Sistem Informasi Kegiatan Luar Kantor. All rights reserved.
        </div>
    </footer>

</body>
</html>