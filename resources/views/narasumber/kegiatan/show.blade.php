@extends('layouts.app')
@section('title', $kegiatan->nama_kegiatan)
@section('page-title', 'Detail Kegiatan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
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
                    <div><p class="text-sm font-medium text-slate-700">{{ $m->judul }}</p><p class="text-xs text-slate-400">{{ strtoupper($m->file_type) }} · {{ $m->file_size_formatted }} · {{ $m->uploader->name }}</p></div>
                </div>
                <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="text-blue-500 hover:text-blue-700"><i class="fas fa-download"></i></a>
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
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-bold">{{ strtoupper(substr($p->name, 0, 1)) }}</div>
                <div><p class="text-sm font-medium text-slate-700">{{ $p->name }}</p><p class="text-xs text-slate-400">{{ $p->biodata->unit_kerja ?? $p->nip }}</p></div>
            </div>
            @empty
            <p class="text-sm text-slate-400">Belum ada peserta</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
