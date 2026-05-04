@extends('layouts.app')
@section('title', 'Materi - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Materi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Upload dan kelola materi kegiatan</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">← Kembali</a>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-upload text-blue-500 mr-2"></i>Upload Materi</h3>
        <form method="POST" action="{{ route('admin.kegiatan.materi.upload', $kegiatan) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="judul" required placeholder="Judul materi" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium hover:file:bg-blue-100">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 text-white text-sm font-medium hover:bg-blue-600 transition-colors">Upload</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($kegiatan->materi as $m)
        <div class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center"><i class="fas fa-file-{{ in_array($m->file_type, ['pdf']) ? 'pdf text-red-500' : 'lines text-blue-500' }}"></i></div>
                <div>
                    <p class="font-medium text-sm text-slate-800">{{ $m->judul }}</p>
                    <p class="text-xs text-slate-400">{{ strtoupper($m->file_type) }} · {{ $m->file_size_formatted }} · {{ $m->uploader->biodata->nama_lengkap ?? $m->uploader->username }}</p>
                </div>
            </div>
            <div class="flex gap-1">
                <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50"><i class="fas fa-download"></i></a>
                <form method="POST" action="{{ route('admin.kegiatan.materi.delete', [$kegiatan, $m]) }}" onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400"><i class="fas fa-file-circle-xmark text-3xl mb-3 block"></i>Belum ada materi</div>
        @endforelse
    </div>
</div>
@endsection
