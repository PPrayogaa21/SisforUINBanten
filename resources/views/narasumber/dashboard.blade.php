@extends('layouts.app')
@section('title', 'Dashboard Narasumber')
@section('page-title', 'Dashboard Narasumber')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20 mb-4">
            <i class="fas fa-chalkboard-user text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $totalKegiatan }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Kegiatan</p>
    </div>
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20 mb-4">
            <i class="fas fa-bolt text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $kegiatanAktif }}</p>
        <p class="text-sm text-slate-500 mt-1">Kegiatan Aktif</p>
    </div>
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/20 mb-4">
            <i class="fas fa-file-lines text-white text-lg"></i>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $totalMateri }}</p>
        <p class="text-sm text-slate-500 mt-1">Materi Diupload</p>
    </div>
</div>

<div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-slate-800">Kegiatan Terbaru</h3>
        <a href="{{ route('narasumber.kegiatan.index') }}" class="text-sm text-emerald-600 font-medium">Lihat Semua →</a>
    </div>
    <div class="space-y-3">
        @forelse($kegiatanDiisi as $item)
        <a href="{{ route('narasumber.kegiatan.show', $item) }}" class="block p-4 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100">
            <p class="font-medium text-slate-800">{{ $item->nama_kegiatan }}</p>
            <p class="text-xs text-slate-400 mt-1"><i class="fas fa-calendar mr-1"></i>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }} · {{ $item->tempat }}</p>
        </a>
        @empty
        <p class="text-sm text-slate-400 text-center py-8">Belum ada kegiatan</p>
        @endforelse
    </div>
</div>
@endsection
