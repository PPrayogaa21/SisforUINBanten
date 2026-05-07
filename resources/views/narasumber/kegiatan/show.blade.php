@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 300px; border-radius: 0.75rem; z-index: 10; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Tombol Kembali --}}
    <div>
        <a href="{{ route('narasumber.kegiatan.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    <!-- Info -->

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->jenis_badge }}">{{ ucfirst($kegiatan->jenis) }}</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->status_badge }}">{{ ucfirst($kegiatan->status) }}</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $kegiatan->nama_kegiatan }}</h2>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50"><i class="fas fa-calendar-days text-emerald-500"></i><div><p class="text-xs text-slate-400">Waktu</p><p class="text-sm font-medium text-slate-700">{{ $kegiatan->waktu_mulai->translatedFormat('d M Y, H:i') }}</p></div></div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50"><i class="fas fa-location-dot text-blue-500"></i><div><p class="text-xs text-slate-400">Tempat</p><p class="text-sm font-medium text-slate-700">{{ $kegiatan->tempat }}</p></div></div>
        </div>

        <!-- Peta Lokasi -->
        @if($kegiatan->latitude && $kegiatan->longitude)
        <div class="mt-4 border border-slate-200 rounded-xl p-1 bg-white">
            <div id="map"></div>
        </div>
        @if($kegiatan->alamat_lengkap)
        <p class="text-sm text-slate-500 mt-2"><i class="fas fa-map-pin text-emerald-500 mr-1"></i> {{ $kegiatan->alamat_lengkap }}</p>
        @endif
        @endif
    </div>

    <!-- Upload Materi -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-upload text-blue-500 mr-2"></i>Upload Materi</h3>
        <form method="POST" action="{{ route('narasumber.kegiatan.materi.upload', $kegiatan) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="judul" required placeholder="Judul materi" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 text-white text-sm font-medium hover:bg-blue-600">Upload</button>
        </form>

        <div class="mt-4 space-y-2">
            @foreach($kegiatan->materi as $m)
            <div class="p-3 rounded-xl bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-lines text-blue-500"></i>
                    <div><p class="text-sm font-medium text-slate-700">{{ $m->judul }}</p><p class="text-xs text-slate-400">{{ strtoupper($m->file_type) }} · {{ $m->file_size_formatted }} · {{ $m->uploader->biodata->nama_lengkap ?? $m->uploader->username }}</p></div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white text-slate-400 hover:text-blue-500 flex items-center justify-center border border-slate-200 transition-all shadow-sm" title="Lihat">
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                    <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white text-slate-400 hover:text-emerald-500 flex items-center justify-center border border-slate-200 transition-all shadow-sm" title="Download">
                        <i class="fas fa-download text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-users text-emerald-500 mr-2"></i>Daftar Peserta ({{ $kegiatan->peserta->count() }})</h3>
        <div class="space-y-2">
            @forelse($kegiatan->peserta as $p)
            <div class="p-3 rounded-xl bg-slate-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-bold">{{ strtoupper(substr($p->biodata->nama_lengkap ?? $p->username ?? 'U', 0, 1)) }}</div>
                <div><p class="text-sm font-medium text-slate-700">{{ $p->biodata->nama_lengkap ?? $p->username }}</p><p class="text-xs text-slate-400">{{ $p->biodata->bagian ?? $p->biodata->nip }}</p></div>
            </div>
            @empty
            <p class="text-sm text-slate-400">Belum ada peserta</p>
            @endforelse
        </div>
    </div>

    <!-- Dokumen Resmi -->
    @if($kegiatan->dokumen->count() > 0)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-file-signature text-amber-500 mr-2"></i>Dokumen Resmi</h3>
        <div class="space-y-3">
            @foreach($kegiatan->dokumen as $doc)
            <div class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 bg-slate-50 group hover:bg-white hover:border-amber-200 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3.5 overflow-hidden pr-2">
                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm text-slate-500">
                        <i class="fas fa-file-contract text-xl"></i>
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-bold text-slate-700 truncate" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase mt-1 tracking-wider">{{ str_replace('_', ' ', $doc->jenis) }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ Storage::disk('public')->url($doc->file_path) }}" target="_blank" class="w-10 h-10 rounded-full bg-white text-slate-600 flex items-center justify-center shrink-0 hover:bg-amber-600 hover:text-white transition-colors border border-slate-300" title="Lihat Dokumen">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('narasumber.dokumen.download', [$kegiatan, $doc]) }}" class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 hover:bg-amber-600 hover:text-white transition-colors border border-amber-100" title="Download Dokumen">
                        <i class="fas fa-download text-sm"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if($kegiatan->latitude && $kegiatan->longitude)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $kegiatan->latitude }};
        const lng = {{ $kegiatan->longitude }};
        const map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>{{ $kegiatan->nama_kegiatan }}</b><br>{{ $kegiatan->tempat }}")
            .openPopup();
    });
</script>
@endpush
@endif

@endsection
