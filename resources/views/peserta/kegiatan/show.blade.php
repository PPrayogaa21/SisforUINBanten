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
@php
    $pesertaPivot = $kegiatan->peserta->where('id', auth()->id())->first();
    $statusKehadiran = $pesertaPivot?->pivot->status_kehadiran;
@endphp

<div class="space-y-6 animate-fade-in">
    <!-- Navigation Header -->
    <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
        <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Dashboard
        </a>
        
        <div class="flex gap-2">
            <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm">
                {{ $kegiatan->jenis ?? 'Kegiatan' }}
            </span>
            <span class="px-3 py-1.5 {{ $kegiatan->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-200' }} border rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm">
                {{ $kegiatan->status ?? 'Published' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Main Info Hero Card -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none transition-transform group-hover:scale-110 duration-700"></div>
                <div class="relative z-10">
                    <div class="inline-block px-3 py-1 bg-emerald-100/50 text-emerald-700 rounded-full text-[10px] font-black tracking-widest uppercase mb-4 border border-emerald-200">
                        Portal Peserta
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight mb-4 leading-tight">
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>

                    @if($kegiatan->deskripsi)
                    <p class="text-slate-500 text-sm leading-relaxed font-medium max-w-3xl mb-2">{{ $kegiatan->deskripsi }}</p>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-100 text-emerald-500 shrink-0">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Jadwal</p>
                                <p class="text-sm font-bold text-slate-700">{{ $kegiatan->waktu_mulai->translatedFormat('l, d F Y') }}</p>
                                <p class="text-xs font-medium text-slate-500">{{ $kegiatan->waktu_mulai->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-100 text-blue-500 shrink-0">
                                <i class="fas fa-map-location text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Lokasi</p>
                                <p class="text-sm font-bold text-slate-700 truncate" title="{{ $kegiatan->tempat }}">{{ $kegiatan->tempat }}</p>
                                <p class="text-xs font-medium text-slate-500">Tatap Muka</p>
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
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</h4>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">{{ $kegiatan->alamat_lengkap }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Materi & File -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm flex flex-col gap-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Materi & Modul</h3>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">Materi yang diunggah oleh narasumber</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($kegiatan->materi as $m)
                    <div class="group flex items-center justify-between p-4 bg-slate-50/50 border border-slate-100 hover:border-blue-200 rounded-2xl hover:shadow-sm hover:bg-white transition-all">
                        <div class="flex items-center gap-4 overflow-hidden">
                            <div class="w-12 h-12 rounded-xl {{ in_array(strtolower($m->file_type), ['pdf','ppt','pptx']) ? 'bg-red-50 text-red-500 border-red-100' : 'bg-blue-50 text-blue-500 border-blue-100' }} border flex items-center justify-center shrink-0 text-lg">
                                <i class="fas fa-{{ in_array(strtolower($m->file_type), ['pdf']) ? 'file-pdf' : (in_array(strtolower($m->file_type), ['ppt','pptx']) ? 'file-powerpoint' : 'file-lines') }}"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-sm font-bold text-slate-800 truncate" title="{{ $m->judul }}">{{ $m->judul }}</h4>
                                <div class="flex items-center gap-2 mt-0.5 text-[10px] font-bold text-slate-400 tracking-wide uppercase">
                                    <span>{{ $m->file_type }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span>{{ round($m->file_size / 1024, 2) }} KB</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pl-4">
                            <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm" title="Buka File">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('peserta.materi.download', $m) }}" class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-md shadow-blue-500/30 hover:bg-blue-600 transition-all" title="Unduh">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-3xl">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                            <i class="fas fa-folder-open text-xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Belum ada materi tersedia</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- ABSENSI STATUS -->
            @if($statusKehadiran !== 'hadir')
            <div class="bg-slate-900 rounded-3xl p-7 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none transition-transform duration-700 group-hover:scale-110">
                    <i class="fas fa-fingerprint text-7xl text-white"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-emerald-400 mb-4 border border-white/10">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">Konfirmasi Absensi</h3>
                    <p class="text-slate-400 text-xs mb-5 font-medium">Klik tombol di bawah untuk mencatat kehadiran Anda pada kegiatan ini.</p>
                    @if($kegiatan->status === 'completed')
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl text-center">
                            <i class="fas fa-calendar-check text-emerald-400 mb-2 block text-xl"></i>
                            <p class="text-white font-bold text-sm">Kegiatan Selesai</p>
                            <p class="text-slate-400 text-[10px]">Absensi sudah ditutup</p>
                        </div>
                    @else
                        <form action="{{ route('peserta.kegiatan.absen', $kegiatan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-extrabold text-sm rounded-2xl transition-all shadow-lg shadow-emerald-500/25 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle text-lg"></i> Absen Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200/70 rounded-3xl p-6 flex items-center gap-4 shadow-sm shadow-emerald-100/50">
                <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-emerald-800 text-base mb-0.5">Terkonfirmasi</h3>
                    <p class="text-emerald-600 text-xs font-semibold">Absensi kehadiran berhasil direkam.</p>
                </div>
            </div>
            @endif

            <!-- PEMATERI -->
            @if($kegiatan->narasumber->count() > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight mb-4 flex items-center gap-2">
                    <i class="fas fa-chalkboard-user text-emerald-500"></i> Pemateri
                </h3>
                <div class="space-y-3">
                    @foreach($kegiatan->narasumber as $n)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3 hover:bg-white transition-colors">
                        <div class="w-10 h-10 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-700 font-black text-xs shrink-0">
                            {{ strtoupper(substr($n->biodata->nama_lengkap ?? $n->username ?? 'U', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $n->biodata->nama_lengkap ?? $n->username }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">{{ $n->pivot->topik_materi ?? 'Pemateri Utama' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- DOKUMEN RESMI -->
            @if($kegiatan->dokumen->count() > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight mb-5 flex items-center gap-2">
                    <i class="fas fa-file-signature text-amber-500"></i> Dokumen Resmi
                </h3>
                <div class="space-y-3">
                    @foreach($kegiatan->dokumen as $doc)
                    <div class="group p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-white hover:border-amber-200 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                                <i class="fas fa-stamp"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-extrabold text-slate-800 truncate uppercase">{{ str_replace('_', ' ', $doc->jenis) }}</h4>
                                <p class="text-[11px] text-slate-400 font-semibold truncate">{{ $doc->judul }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            @if($statusKehadiran === 'hadir')
                                <a href="{{ route('peserta.dokumen.view', [$kegiatan, $doc]) }}" target="_blank" class="flex items-center justify-center py-2 bg-white border border-slate-200 text-[11px] font-bold text-slate-600 rounded-xl hover:bg-slate-50">
                                    <i class="fas fa-eye mr-1.5"></i> Lihat
                                </a>
                                <a href="{{ route('peserta.dokumen.download', [$kegiatan, $doc]) }}" class="flex items-center justify-center py-2 bg-amber-500 text-white text-[11px] font-bold rounded-xl hover:bg-amber-600 shadow-sm shadow-amber-500/20">
                                    <i class="fas fa-download mr-1.5"></i> Unduh
                                </a>
                            @else
                                <button type="button" onclick="alert('Anda harus mengonfirmasi kehadiran terlebih dahulu untuk mengakses dokumen ini.')" class="flex items-center justify-center py-2 bg-slate-100 border border-slate-200 text-[11px] font-bold text-slate-400 rounded-xl cursor-not-allowed">
                                    <i class="fas fa-eye mr-1.5"></i> Lihat
                                </button>
                                <button type="button" onclick="alert('Anda harus mengonfirmasi kehadiran terlebih dahulu untuk mengakses dokumen ini.')" class="flex items-center justify-center py-2 bg-slate-200 text-slate-500 text-[11px] font-bold rounded-xl cursor-not-allowed">
                                    <i class="fas fa-download mr-1.5"></i> Unduh
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- DOKUMENTASI FOTO -->
            @if($kegiatan->dokumentasi->count() > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="fas fa-images text-indigo-500"></i> Galeri & Foto
                    </h3>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    @foreach($kegiatan->dokumentasi as $foto)
                    <div class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-100 bg-slate-50">
                        <img src="{{ asset('storage/' . $foto->file_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px] gap-2.5">
                            <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank" class="w-9 h-9 bg-white/20 hover:bg-white text-white hover:text-slate-900 rounded-xl flex items-center justify-center transition-all duration-200 shadow-sm backdrop-blur-sm">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('peserta.dokumentasi.download', $foto->id) }}" class="w-9 h-9 bg-emerald-500/80 hover:bg-emerald-500 text-white rounded-xl flex items-center justify-center transition-all duration-200 shadow-md backdrop-blur-sm">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- EVALUASI KUESIONER -->
            @php
                $activeQ = $kegiatan->kuesioner->filter(fn($q) => $q->is_active);
                $showQ = in_array($kegiatan->status, ['completed', 'ongoing', 'published']) && $activeQ->count() > 0;
            @endphp
            
            @if($showQ)
            <div class="bg-emerald-900 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-700/30 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 mb-2">
                        <i class="fas fa-star text-amber-300"></i> Evaluasi Acara
                    </h3>
                    <p class="text-emerald-200/80 text-[11px] mb-5 font-medium leading-relaxed">Luangkan waktu sebentar untuk mengisi instrumen evaluasi kegiatan ini.</p>
                    
                    <div class="space-y-2.5">
                        @foreach($activeQ as $item)
                            @php $hasFilled = $item->responses()->where('user_id', auth()->id())->exists(); @endphp
                            @if($hasFilled)
                                <div class="bg-emerald-950/40 border border-emerald-800/50 rounded-2xl p-3.5 flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-xs">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <p class="text-xs font-bold text-emerald-100 truncate">{{ $item->judul }}</p>
                                </div>
                            @else
                                <a href="{{ route('peserta.kuesioner.fill', [$kegiatan, $item]) }}" class="w-full bg-white hover:bg-emerald-50 text-slate-800 rounded-2xl px-4 py-3 font-bold text-xs transition-all flex items-center justify-between group">
                                    <span class="truncate">{{ $item->judul }}</span>
                                    <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0 group-hover:translate-x-0.5 transition-transform text-emerald-600">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

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
        
        const map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Custom Pulse Icon mapped to Emerald Theme
        const icon = L.divIcon({
            html: `<div class="relative flex items-center justify-center"><div class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-emerald-400 opacity-75"></div><div class="relative inline-flex rounded-full h-5 w-5 bg-emerald-600 border-2 border-white shadow-lg"></div></div>`,
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
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); }
    .leaflet-popup-content { margin: 12px 16px; }
</style>
@endif
@endpush

@endsection
