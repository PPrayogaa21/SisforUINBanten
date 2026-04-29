@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan')

@section('content')
@php
    $peserta = $kegiatan->peserta->where('id', auth()->id())->first();
    $status = $peserta?->pivot->status_kehadiran;
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->jenis_badge }}">{{ ucfirst($kegiatan->jenis) }}</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->status_badge }}">{{ ucfirst($kegiatan->status) }}</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">{{ $kegiatan->nama_kegiatan }}</h2>
        @if($kegiatan->deskripsi)<p class="text-slate-600 text-sm mb-4">{{ $kegiatan->deskripsi }}</p>@endif

        <div class="grid sm:grid-cols-2 gap-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                <i class="fas fa-calendar-days text-emerald-500"></i>
                <div><p class="text-xs text-slate-400">Waktu</p><p class="text-sm font-medium text-slate-700">{{ $kegiatan->waktu_mulai->translatedFormat('d M Y, H:i') }}</p></div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                <i class="fas fa-location-dot text-blue-500"></i>
                <div><p class="text-xs text-slate-400">Tempat</p><p class="text-sm font-medium text-slate-700">{{ $kegiatan->tempat }}</p></div>
            </div>
        </div>
    </div>
    <!-- ABSENSI -->
    @if($status !== 'hadir')
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-3">
            <i class="fas fa-user-check text-emerald-500 mr-2"></i>Absensi
        </h3>

        <form action="{{ route('peserta.kegiatan.absen', $kegiatan->id) }}" method="POST">
            @csrf
            <button type="submit" 
                class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600 transition">
                Absen Sekarang
            </button>
        </form>
    </div>
    @else
    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700">
        <i class="fas fa-check-circle mr-2"></i>
        Kamu sudah absen
    </div>
    @endif
    <!-- Narasumber -->
    @if($kegiatan->narasumber->count() > 0)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-chalkboard-user text-amber-500 mr-2"></i>Narasumber</h3>
        <div class="space-y-2">
            @foreach($kegiatan->narasumber as $n)
            <div class="p-3 rounded-xl bg-slate-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-xs font-bold">{{ strtoupper(substr($n->name, 0, 1)) }}</div>
                <div><p class="text-sm font-medium text-slate-700">{{ $n->name }}</p><p class="text-xs text-slate-400">{{ $n->pivot->topik_materi ?? '' }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Materi -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-file-lines text-blue-500 mr-2"></i>Materi Kegiatan</h3>
        @forelse($kegiatan->materi as $m)
        <div class="p-3 rounded-xl bg-slate-50 flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
                <i class="fas fa-file-{{ $m->file_type === 'pdf' ? 'pdf text-red-500' : 'lines text-blue-500' }}"></i>
                <div><p class="text-sm font-medium text-slate-700">{{ $m->judul }}</p><p class="text-xs text-slate-400">{{ strtoupper($m->file_type) }} · {{ round($m->file_size / 1024, 2) }} KB</p></div>
            </div>
            <a href="{{ route('peserta.materi.download', $m) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-blue-100"><i class="fas fa-download mr-1"></i>Download</a>
        </div>
        @empty
        <p class="text-sm text-slate-400">Belum ada materi</p>
        @endforelse
    </div>

    <!-- Dokumen Resmi -->
    @if($kegiatan->dokumen->count() > 0)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-file-pdf text-red-500 mr-2"></i>Dokumen Resmi</h3>
        @foreach($kegiatan->dokumen as $doc)
        <div class="p-3 rounded-xl bg-slate-50 flex items-center justify-between mb-2">
            <div><p class="text-sm font-medium text-slate-700">{{ $doc->judul }}</p><p class="text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $doc->jenis)) }}</p></div>
            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-500 hover:text-blue-700"><i class="fas fa-external-link"></i></a>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Kuesioner -->
    @if($kegiatan->status === 'completed' && $kegiatan->kuesioner->count() > 0)
    <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg">
        <h3 class="font-semibold mb-2"><i class="fas fa-clipboard-list mr-2"></i>Kuesioner Evaluasi</h3>
        <p class="text-emerald-100 text-sm mb-4">Berikan penilaian Anda terhadap kegiatan ini</p>
        @foreach($kegiatan->kuesioner as $q)
            @php $filled = $q->responses()->where('user_id', auth()->id())->exists(); @endphp
            @if($filled)
                <p class="text-sm text-emerald-200"><i class="fas fa-check-circle mr-1"></i>Anda sudah mengisi kuesioner "{{ $q->judul }}"</p>
            @else
                <a href="{{ route('peserta.kuesioner.fill', [$kegiatan, $q]) }}" class="inline-block px-5 py-2.5 rounded-xl bg-white text-emerald-600 font-medium text-sm hover:bg-emerald-50 transition-colors">Isi Kuesioner: {{ $q->judul }}</a>
            @endif
        @endforeach
    </div>
    @endif
</div>
@endsection
