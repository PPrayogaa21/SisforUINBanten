@extends('layouts.app')
@section('title', 'Dashboard Peserta')
@section('page-title', 'Dashboard Peserta')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 mb-4">
            <i class="fas fa-calendar-check text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $totalKegiatanDiikuti }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Kegiatan Diikuti</p>
    </div>
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20 mb-4">
            <i class="fas fa-bolt text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $kegiatanAktif }}</p>
        <p class="text-sm text-slate-500 mt-1">Kegiatan Aktif</p>
    </div>
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/20 mb-4">
            <i class="fas fa-circle-check text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $kegiatanSelesai }}</p>
        <p class="text-sm text-slate-500 mt-1">Kegiatan Selesai</p>
    </div>
</div>

<div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-slate-800">Kegiatan Terbaru</h3>
        <a href="{{ route('peserta.kegiatan.index') }}" class="text-sm text-emerald-600 font-medium">Lihat Semua →</a>
    </div>
    <div class="space-y-3">
        @forelse($kegiatanDiikuti as $item)
        <a href="{{ route('peserta.kegiatan.show', $item) }}" class="block p-4 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-800">{{ $item->nama_kegiatan }}</p>
                    <p class="text-xs text-slate-400 mt-1"><i class="fas fa-calendar mr-1"></i>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }} · {{ $item->tempat }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span>
            </div>
        </a>
        @empty
        <p class="text-sm text-slate-400 text-center py-8">Belum ada kegiatan yang diikuti</p>
        @endforelse
    </div>
</div>
@endsection
