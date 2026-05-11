@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 350px; border-radius: 1.5rem; z-index: 10; }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Navigation Header -->
    <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
        <a href="{{ route('narasumber.kegiatan.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-amber-600 transition-colors bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
        </a>
        
        <div class="flex gap-2">
            <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm">
                {{ $kegiatan->jenis }}
            </span>
            <span class="px-3 py-1.5 {{ $kegiatan->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-200' }} border rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm">
                {{ $kegiatan->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Main Info Hero Card -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none transition-transform group-hover:scale-110 duration-700"></div>
                <div class="relative z-10">
                    <div class="inline-block px-3 py-1 bg-amber-100/50 text-amber-700 rounded-full text-[10px] font-black tracking-widest uppercase mb-4 border border-amber-200">
                        Portal Narasumber
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight mb-4 leading-tight">
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-100 text-emerald-500 shrink-0">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Tanggal & Waktu</p>
                                <p class="text-sm font-bold text-slate-700">{{ $kegiatan->waktu_mulai->translatedFormat('l, d F Y') }}</p>
                                <p class="text-xs font-medium text-slate-500">{{ $kegiatan->waktu_mulai->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-100 text-blue-500 shrink-0">
                                <i class="fas fa-map-location text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Tempat Acara</p>
                                <p class="text-sm font-bold text-slate-700 truncate max-w-[150px]" title="{{ $kegiatan->tempat }}">{{ $kegiatan->tempat }}</p>
                                <p class="text-xs font-medium text-slate-500">Pertemuan Offline</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map View Card -->
            @if($kegiatan->latitude && $kegiatan->longitude)
            <div class="bg-white rounded-3xl p-2 border border-slate-200/60 shadow-sm relative overflow-hidden">
                <div id="map" class="w-full relative z-10 shadow-inner"></div>
                
                @if($kegiatan->alamat_lengkap)
                <div class="p-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500 shrink-0 mt-1">
                        <i class="fas fa-map-pin text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Presisi</h4>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">{{ $kegiatan->alamat_lengkap }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Section Upload & List Materi -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm flex flex-col gap-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Manajemen Materi</h3>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">Dokumen presentasi untuk peserta</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                        <i class="fas fa-cloud-arrow-up"></i>
                    </div>
                </div>

                <!-- Input Form -->
                <form method="POST" action="{{ route('narasumber.kegiatan.materi.upload', $kegiatan) }}" enctype="multipart/form-data" class="bg-slate-50 rounded-2xl p-5 border border-slate-100 flex flex-col md:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="judul" required placeholder="Ketik Judul Materi..." class="w-full px-4 py-3 bg-white rounded-xl border border-slate-200 text-sm font-medium placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                    </div>
                    <div class="relative group">
                        <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip" class="absolute inset-0 opacity-0 w-full cursor-pointer z-20">
                        <div class="h-full flex items-center px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-500 group-hover:border-blue-300 transition-colors z-10 relative overflow-hidden whitespace-nowrap">
                            <i class="fas fa-paperclip mr-2 text-blue-400"></i> Pilih File
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 active:scale-95">
                        Unggah
                    </button>
                </form>

                <!-- Material Listing -->
                <div class="space-y-3 mt-2">
                    @forelse($kegiatan->materi as $m)
                    <div class="group flex items-center justify-between p-4 bg-white border border-slate-100 hover:border-blue-200 rounded-2xl hover:shadow-sm hover:bg-blue-50/10 transition-all">
                        <div class="flex items-center gap-4 overflow-hidden">
                            <div class="w-12 h-12 rounded-xl {{ in_array(strtolower($m->file_type), ['pdf','ppt','pptx']) ? 'bg-red-50 text-red-500 border-red-100' : 'bg-blue-50 text-blue-500 border-blue-100' }} border flex items-center justify-center shrink-0 text-lg">
                                <i class="fas fa-{{ in_array(strtolower($m->file_type), ['pdf']) ? 'file-pdf' : (in_array(strtolower($m->file_type), ['ppt','pptx']) ? 'file-powerpoint' : 'file-lines') }}"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-sm font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors" title="{{ $m->judul }}">{{ $m->judul }}</h4>
                                <div class="flex items-center gap-2 mt-0.5 text-[10px] font-bold text-slate-400 tracking-wide uppercase">
                                    <span>{{ $m->file_type }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span>{{ $m->uploader->biodata->nama_lengkap ?? $m->uploader->username }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 pl-4">
                            <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-xs" title="Lihat File">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            @if($m->uploaded_by == auth()->id())
                            <form method="POST" action="{{ route('narasumber.kegiatan.materi.delete', [$kegiatan, $m]) }}" class="inline form-delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all shadow-xs" title="Hapus Materi">
                                    <i class="fas fa-trash-can text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-3xl">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                            <i class="fas fa-folder-open text-xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Belum ada materi diunggah</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Galeri & Foto Section -->
            @if($kegiatan->dokumentasi->count() > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="fas fa-images text-indigo-500"></i> Galeri & Foto
                    </h3>
                    <span class="text-[11px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-100">{{ $kegiatan->dokumentasi->count() }} Foto</span>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    @foreach($kegiatan->dokumentasi as $foto)
                    <div class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-100 bg-slate-50">
                        <img src="{{ asset('storage/' . $foto->file_path) }}" alt="{{ $foto->caption ?? 'Dokumentasi' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px] gap-3">
                            <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank" class="w-9 h-9 bg-white/20 hover:bg-white text-white hover:text-slate-900 rounded-xl flex items-center justify-center transition-all duration-200 shadow-sm backdrop-blur-sm" title="Buka Foto">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('narasumber.dokumentasi.download', $foto->id) }}" class="w-9 h-9 bg-emerald-500/80 hover:bg-emerald-500 text-white rounded-xl flex items-center justify-center transition-all duration-200 shadow-md backdrop-blur-sm" title="Download Foto">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Dokumen Khusus Resmi (Surat Tugas, dll) -->
            @if($kegiatan->dokumen->count() > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight mb-5 flex items-center gap-2">
                    <i class="fas fa-file-signature text-amber-500"></i> Dokumen Resmi
                </h3>
                <div class="space-y-3">
                    @foreach($kegiatan->dokumen as $doc)
                    <div class="group p-4 bg-gradient-to-br from-slate-50 to-white border border-slate-100 rounded-2xl hover:border-amber-200 transition-all relative overflow-hidden">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0 text-lg group-hover:rotate-6 transition-transform">
                                <i class="fas fa-stamp"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-extrabold text-slate-800 truncate uppercase tracking-wider">{{ str_replace('_', ' ', $doc->jenis) }}</h4>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5 line-clamp-1" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <a href="{{ Storage::disk('public')->url($doc->file_path) }}" target="_blank" class="flex items-center justify-center gap-2 py-2 bg-white border border-slate-200 text-[11px] font-bold text-slate-600 rounded-xl hover:bg-slate-50 transition-colors shadow-xs">
                                <i class="fas fa-eye text-[10px]"></i> Lihat
                            </a>
                            <a href="{{ route('narasumber.dokumen.download', [$kegiatan, $doc]) }}" class="flex items-center justify-center gap-2 py-2 bg-amber-500 text-white border border-amber-500 text-[11px] font-bold rounded-xl hover:bg-amber-600 transition-all shadow-md shadow-amber-500/20">
                                <i class="fas fa-download text-[10px]"></i> Ambil
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Participant Summary -->
            <div class="bg-slate-900 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <!-- Abstract blob -->
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-white">Data Peserta</h3>
                        <p class="text-[10px] text-emerald-400/80 font-bold tracking-wider uppercase">Terkonfirmasi</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30">
                        <span class="text-sm font-black">{{ $kegiatan->peserta->count() }}</span>
                    </div>
                </div>

                <div class="relative z-10 max-h-64 overflow-y-auto custom-scrollbar pr-1 space-y-2">
                    @forelse($kegiatan->peserta as $p)
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-black ring-2 ring-slate-800">
                            {{ strtoupper(substr($p->biodata->nama_lengkap ?? $p->username ?? 'U', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-[11px] font-bold text-slate-200 truncate">{{ $p->biodata->nama_lengkap ?? $p->username }}</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">{{ $p->biodata->bagian ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs font-bold text-slate-500 text-center py-4 italic">Belum ada data peserta</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
@if($kegiatan->latitude && $kegiatan->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $kegiatan->latitude }};
        const lng = {{ $kegiatan->longitude }};
        
        // Fancy Dark/Light Hybrid tile via Voyager
        const map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Custom Glowing Marker
        const icon = L.divIcon({
            html: `<div class="relative flex items-center justify-center"><div class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-amber-400 opacity-75"></div><div class="relative inline-flex rounded-full h-5 w-5 bg-amber-600 border-2 border-white shadow-lg"></div></div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        L.marker([lat, lng], {icon: icon}).addTo(map)
            .bindPopup("<div class='font-sans font-bold text-slate-800'>{{ $kegiatan->nama_kegiatan }}</div>")
            .openPopup();
    });
</script>
<style>
    /* Custom leaflet theme */
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .leaflet-popup-content { margin: 12px 15px; }
    /* Scrollbar custom */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }
</style>
@endif

<!-- SweetAlert2 config -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Materi?',
                    text: "Materi ini akan hilang permanen dari riwayat.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', // red-600
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl font-sans',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-sm',
                        cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>
@endpush
@endsection
