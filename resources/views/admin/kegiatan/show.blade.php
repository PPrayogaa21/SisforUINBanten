@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->jenis_badge }}">{{ ucfirst($kegiatan->jenis) }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $kegiatan->status_badge }}">{{ ucfirst($kegiatan->status) }}</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
                <p class="text-slate-500 text-sm mt-1">Dibuat oleh {{ $kegiatan->creator->biodata->nama_lengkap ?? $kegiatan->creator->username }} · {{ $kegiatan->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.kegiatan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm shadow-sm transition-all">
                    <i class="fas fa-arrow-left text-slate-400"></i> Kembali
                </a>
                <a href="{{ route('admin.kegiatan.edit', $kegiatan) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-50 text-blue-700 font-semibold text-sm shadow-sm transition-all">
                    <i class="fas fa-pen text-blue-400"></i> Edit
                </a>
            </div>
        </div>

        @if($kegiatan->deskripsi)
        <p class="text-slate-600 mt-4 text-sm leading-relaxed">{{ $kegiatan->deskripsi }}</p>
        @endif

        <div class="grid sm:grid-cols-3 gap-4 mt-6">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                <i class="fas fa-calendar-days text-emerald-500"></i>
                <div>
                    <p class="text-xs text-slate-400">Waktu</p>
                    <p class="text-sm font-medium text-slate-700">{{ $kegiatan->waktu_mulai->format('d/m/Y H:i') }} - {{ $kegiatan->waktu_selesai->format('H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                <i class="fas fa-location-dot text-blue-500"></i>
                <div>
                    <p class="text-xs text-slate-400">Tempat</p>
                    <p class="text-sm font-medium text-slate-700">{{ $kegiatan->tempat }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                <i class="fas fa-users text-amber-500"></i>
                <div>
                    <p class="text-xs text-slate-400">Peserta</p>
                    <p class="text-sm font-medium text-slate-700">{{ $kegiatan->peserta->count() }} orang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('admin.kegiatan.peserta', $kegiatan) }}" class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm text-center hover:shadow-md hover:border-emerald-200 transition-all group">
            <i class="fas fa-users text-lg text-emerald-500 group-hover:scale-110 transition-transform block mb-1"></i>
            <span class="text-xs font-medium text-slate-600">Peserta ({{ $kegiatan->peserta->count() }})</span>
        </a>
        <a href="{{ route('admin.kegiatan.narasumber', $kegiatan) }}" class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm text-center hover:shadow-md hover:border-amber-200 transition-all group">
            <i class="fas fa-chalkboard-user text-lg text-amber-500 group-hover:scale-110 transition-transform block mb-1"></i>
            <span class="text-xs font-medium text-slate-600">Narasumber ({{ $kegiatan->narasumber->count() }})</span>
        </a>
        <a href="{{ route('admin.kegiatan.materi', $kegiatan) }}" class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm text-center hover:shadow-md hover:border-blue-200 transition-all group">
            <i class="fas fa-file-lines text-lg text-blue-500 group-hover:scale-110 transition-transform block mb-1"></i>
            <span class="text-xs font-medium text-slate-600">Materi ({{ $kegiatan->materi->count() }})</span>
        </a>
        <a href="{{ route('admin.kegiatan.dokumentasi', $kegiatan) }}" class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm text-center hover:shadow-md hover:border-purple-200 transition-all group">
            <i class="fas fa-images text-lg text-purple-500 group-hover:scale-110 transition-transform block mb-1"></i>
            <span class="text-xs font-medium text-slate-600">Foto ({{ $kegiatan->dokumentasi->count() }})</span>
        </a>
        <a href="{{ route('admin.kegiatan.dokumen', $kegiatan) }}" class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm text-center hover:shadow-md hover:border-red-200 transition-all group">
            <i class="fas fa-file-pdf text-lg text-red-500 group-hover:scale-110 transition-transform block mb-1"></i>
            <span class="text-xs font-medium text-slate-600">Dokumen ({{ $kegiatan->dokumen->count() }})</span>
        </a>
    </div>

    <!-- Kuesioner -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800"><i class="fas fa-clipboard-list text-emerald-500 mr-2"></i>Kuesioner</h3>
            <a href="{{ route('admin.kegiatan.kuesioner', $kegiatan) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Kelola →</a>
        </div>
        @if($kegiatan->kuesioner->count() > 0)
            @foreach($kegiatan->kuesioner as $q)
            <div class="p-3 rounded-xl bg-slate-50 flex items-center justify-between mb-2">
                <span class="text-sm text-slate-700">{{ $q->judul }}</span>
                <span class="text-xs text-slate-400">{{ $q->responses->count() }} respons</span>
            </div>
            @endforeach
        @else
            <p class="text-sm text-slate-400">Belum ada kuesioner</p>
        @endif
    </div>
</div>
@endsection
