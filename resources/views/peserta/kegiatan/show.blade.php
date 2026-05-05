@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan Peserta')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 350px; border-radius: 1rem; z-index: 10; }
</style>
@endpush

@section('content')
@php
    $peserta = $kegiatan->peserta->where('id', auth()->id())->first();
    $status = $peserta?->pivot->status_kehadiran;
@endphp

<!-- Navigation Header -->
<div class="mb-6">
    <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    
    <!-- LEFT COLUMN: Main Info & Map -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Hero Card -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm relative overflow-hidden">
            <!-- Decorative Blob -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-2 mb-5">
                    <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200 shadow-sm">
                        {{ $kegiatan->jenis ?? 'Kegiatan' }}
                    </span>
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider border border-blue-200 shadow-sm">
                        {{ $kegiatan->status ?? 'Published' }}
                    </span>
                </div>
                
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-800 leading-tight mb-4 tracking-tight">
                    {{ $kegiatan->nama_kegiatan }}
                </h1>
                
                @if($kegiatan->deskripsi)
                <p class="text-slate-600 leading-relaxed mb-8 text-sm md:text-base">
                    {{ $kegiatan->deskripsi }}
                </p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 lg:p-5 rounded-2xl flex items-center gap-4 border border-slate-100">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-emerald-500 shrink-0">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jadwal Pelaksanaan</p>
                            <p class="text-sm font-bold text-slate-700">{{ $kegiatan->waktu_mulai->translatedFormat('d F Y') }}</p>
                            <p class="text-xs font-medium text-slate-500">{{ $kegiatan->waktu_mulai->format('H:i') }} WIB - Selesai</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-4 lg:p-5 rounded-2xl flex items-center gap-4 border border-slate-100">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-blue-500 shrink-0">
                            <i class="fas fa-location-dot text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi Kegiatan</p>
                            <p class="text-sm font-bold text-slate-700 line-clamp-1">{{ $kegiatan->tempat }}</p>
                            <p class="text-xs font-medium text-slate-500 line-clamp-1">Tatap Muka (Offline)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta Lokasi -->
        @if($kegiatan->latitude && $kegiatan->longitude)
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-map-location-dot text-slate-400"></i> Denah Lokasi
            </h3>
            <div class="border-4 border-slate-50 rounded-2xl overflow-hidden shadow-inner relative">
                <div id="map"></div>
            </div>
            @if($kegiatan->alamat_lengkap)
            <div class="mt-5 flex items-start gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-rose-500 shrink-0 shadow-sm">
                    <i class="fas fa-map-pin text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-slate-700 font-medium leading-relaxed">{{ $kegiatan->alamat_lengkap }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif
        
    </div>

    <!-- RIGHT COLUMN: Actions & Info -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- ABSENSI CARD -->
        @if($status !== 'hadir')
        <div class="bg-slate-900 rounded-3xl p-7 shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none transition-transform duration-700 group-hover:scale-110">
                <i class="fas fa-fingerprint text-8xl text-white"></i>
            </div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-5 backdrop-blur-sm border border-white/10">
                    <i class="fas fa-clipboard-user text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Presensi Kehadiran</h3>
                <p class="text-slate-400 text-sm mb-6 leading-relaxed">Konfirmasi kehadiran Anda sekarang agar tercatat di sistem sebagai peserta aktif.</p>
                
                <form action="{{ route('peserta.kegiatan.absen', $kegiatan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2 group/btn">
                        <i class="fas fa-check-circle group-hover/btn:scale-110 transition-transform"></i> Absen Sekarang
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-6 flex items-start gap-4">
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-emerald-500 shrink-0 mt-0.5 border border-emerald-100 shadow-sm">
                <i class="fas fa-check text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-emerald-800 text-lg mb-1">Terkonfirmasi</h3>
                <p class="text-emerald-600 text-sm leading-relaxed font-medium">Anda sudah berhasil melakukan absensi kehadiran. Selamat mengikuti acara!</p>
            </div>
        </div>
        @endif

        <!-- Narasumber -->
        @if($kegiatan->narasumber->count() > 0)
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chalkboard-user text-slate-400"></i> Pemateri
            </h3>
            <div class="space-y-3">
                @foreach($kegiatan->narasumber as $n)
                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-slate-300 hover:shadow-sm transition-all flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-slate-600 font-bold shrink-0 border border-slate-200 shadow-sm group-hover:text-amber-600 group-hover:border-amber-200 transition-colors">
                        {{ strtoupper(substr($n->biodata->nama_lengkap ?? $n->username ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $n->biodata->nama_lengkap ?? $n->username }}</p>
                        @if($n->pivot->topik_materi)
                            <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $n->pivot->topik_materi }}</p>
                        @else
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Pemateri Utama</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Materi Kegiatan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-folder-open text-slate-400"></i> Materi & File
            </h3>
            
            @if($kegiatan->materi->count() > 0)
                <div class="space-y-3">
                    @foreach($kegiatan->materi as $m)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 bg-slate-50 group hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all">
                        <div class="flex items-center gap-3.5 overflow-hidden pr-2">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-file-{{ $m->file_type === 'pdf' ? 'pdf text-red-500' : 'lines text-blue-500' }} text-xl"></i>
                            </div>
                            <div class="truncate">
                                <p class="text-sm font-bold text-slate-700 truncate" title="{{ $m->judul }}">{{ $m->judul }}</p>
                                <p class="text-[11px] font-semibold text-slate-400 uppercase mt-1 tracking-wider">{{ $m->file_type }} • {{ round($m->file_size / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 hover:bg-slate-200 transition-colors border border-slate-200" title="Lihat Materi">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('peserta.materi.download', $m) }}" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 hover:bg-blue-600 hover:text-white transition-colors border border-blue-100" title="Download Materi">
                                <i class="fas fa-download text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                    <i class="fas fa-folder-minus text-3xl text-slate-300 mb-2"></i>
                    <p class="text-sm text-slate-500 font-medium">Belum ada materi</p>
                </div>
            @endif
        </div>

        <!-- Dokumen Resmi -->
        @if($kegiatan->dokumen->count() > 0)
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-file-signature text-slate-400"></i> Dokumen Resmi
            </h3>
            <div class="space-y-3">
                @foreach($kegiatan->dokumen as $doc)
                <div class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 bg-slate-50 group hover:bg-white hover:border-slate-300 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3.5 overflow-hidden pr-2">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm text-slate-500">
                            <i class="fas fa-file-contract text-xl"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-bold text-slate-700 truncate" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase mt-1 tracking-wider">{{ str_replace('_', ' ', $doc->jenis) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('peserta.dokumen.download', [$kegiatan, $doc]) }}" class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 hover:bg-slate-700 hover:text-white transition-colors border border-slate-300" title="Download Dokumen">
                        <i class="fas fa-download text-sm"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Dokumentasi / Foto -->
        @if($kegiatan->dokumentasi->count() > 0)
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-images text-slate-400"></i> Galeri & Foto
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-3">
                @foreach($kegiatan->dokumentasi as $foto)
                <div class="relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm aspect-square">
                    <img src="{{ asset('storage/' . $foto->file_path) }}" alt="{{ $foto->caption ?? 'Dokumentasi Kegiatan' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                        <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-slate-900 flex items-center justify-center backdrop-blur-sm transition-colors" title="Lihat Foto">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        <a href="{{ route('peserta.dokumentasi.download', $foto->id) }}" class="w-8 h-8 rounded-full bg-emerald-500/80 hover:bg-emerald-500 text-white flex items-center justify-center backdrop-blur-sm transition-colors" title="Download Foto">
                            <i class="fas fa-download text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Kuesioner Evaluasi -->
        @php
            $kuesionerAktif = $kegiatan->kuesioner->filter(fn($q) => $q->is_active);
            $tampilKuesioner = in_array($kegiatan->status, ['completed', 'ongoing', 'published']) && $kuesionerAktif->count() > 0;
        @endphp
        
        @if($tampilKuesioner)
        <div class="bg-slate-900 rounded-3xl p-6 shadow-lg text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                <i class="fas fa-clipboard-question text-8xl text-white"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                    <i class="fas fa-star text-amber-300"></i> Evaluasi Kegiatan
                </h3>
                <p class="text-slate-400 text-sm mb-5 leading-relaxed">Masukan Anda sangat berarti bagi pengembangan kegiatan kami ke depannya.</p>
                
                <div class="space-y-3">
                    @foreach($kuesionerAktif as $q)
                        @php $filled = $q->responses()->where('user_id', auth()->id())->exists(); @endphp
                        
                        @if($filled)
                            <div class="bg-white/10 border border-white/10 rounded-xl p-3.5 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <p class="text-xs font-semibold leading-tight text-white">Selesai: {{ $q->judul }}</p>
                            </div>
                        @else
                            <a href="{{ route('peserta.kuesioner.fill', [$kegiatan, $q]) }}" class="w-full bg-white hover:bg-slate-100 text-slate-900 rounded-xl px-4 py-3.5 font-bold text-sm transition-all flex items-center justify-between group">
                                <span class="line-clamp-1 truncate pr-2 text-left">Isi: {{ $q->judul }}</span>
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition-colors shrink-0">
                                    <i class="fas fa-arrow-right text-slate-600 text-xs group-hover:translate-x-0.5 transition-transform"></i>
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

@if($kegiatan->latitude && $kegiatan->longitude)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $kegiatan->latitude }};
        const lng = {{ $kegiatan->longitude }};
        const map = L.map('map').setView([lat, lng], 15);

        // Modern base map style
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        // Custom UI Marker
        const customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #059669; width: 28px; height: 28px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);"></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        L.marker([lat, lng], {icon: customIcon}).addTo(map)
            .bindPopup("<div class='font-bold text-slate-800 text-[13px] mb-1 leading-tight'>{{ $kegiatan->nama_kegiatan }}</div><div class='text-[11px] text-slate-500 font-medium'>{{ $kegiatan->tempat }}</div>")
            .openPopup();
    });
</script>
<style>
    /* Styling leaflet popup to match modern design */
    .leaflet-popup-content-wrapper { border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); border: 1px solid #f1f5f9; }
    .leaflet-popup-content { margin: 12px 16px; line-height: 1.4; }
    .leaflet-container a.leaflet-popup-close-button { color: #94a3b8; padding: 6px; }
    .leaflet-container a.leaflet-popup-close-button:hover { color: #475569; }
</style>
@endpush
@endif

@endsection
