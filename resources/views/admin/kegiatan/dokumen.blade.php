@extends('layouts.app')
@section('title', 'Dokumen - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Dokumen')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Upload surat tugas, undangan, dan dokumen resmi</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 font-medium">← Kembali</a>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-file-upload text-red-500 mr-2"></i>Upload Dokumen</h3>
        <form method="POST" action="{{ route('admin.kegiatan.dokumen.upload', $kegiatan) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="judul" required placeholder="Judul dokumen" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <select name="jenis" required class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                <option value="surat_tugas">Surat Tugas</option>
                <option value="undangan">Undangan</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <input type="file" name="file" required accept=".pdf,.doc,.docx" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-red-50 file:text-red-600 file:font-medium">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-white text-sm font-medium hover:bg-red-600">Upload</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($kegiatan->dokumen as $doc)
        <div class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center"><i class="fas fa-file-pdf text-red-500"></i></div>
                <div>
                    <p class="font-medium text-sm text-slate-800">{{ $doc->judul }}</p>
                    <p class="text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $doc->jenis)) }}</p>
                </div>
            </div>
            <div class="flex gap-1">
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600"><i class="fas fa-download"></i></a>
                <form method="POST" action="{{ route('admin.kegiatan.dokumen.delete', [$kegiatan, $doc]) }}" onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400">Belum ada dokumen</div>
        @endforelse
    </div>
</div>
@endsection
