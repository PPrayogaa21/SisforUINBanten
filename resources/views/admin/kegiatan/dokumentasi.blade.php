@extends('layouts.app')
@section('title', 'Dokumentasi - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Dokumentasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Upload foto dokumentasi kegiatan</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}"
        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium shadow-sm transition">
            
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>

            Kembali
        </a>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-camera text-purple-500 mr-2"></i>Upload Foto</h3>
        <form method="POST" action="{{ route('admin.kegiatan.dokumentasi.upload', $kegiatan) }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="text" name="caption" placeholder="Caption (opsional)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <input type="file" name="files[]" multiple required accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-600 file:font-medium">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-500 text-white text-sm font-medium hover:bg-purple-600 transition-colors">Upload Foto</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @forelse($kegiatan->dokumentasi as $dok)
        <div class="group relative aspect-square rounded-xl overflow-hidden border border-slate-200/50">
            <img src="{{ asset('storage/' . $dok->file_path) }}" alt="{{ $dok->caption }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <form method="POST" action="{{ route('admin.kegiatan.dokumentasi.delete', [$kegiatan, $dok]) }}" onsubmit="return confirm('Hapus foto ini?')">
                    @csrf @method('DELETE')
                    <button class="p-3 rounded-full bg-red-500 text-white hover:bg-red-600"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @if($dok->caption)
            <p class="absolute bottom-0 left-0 right-0 p-2 bg-black/50 text-white text-xs">{{ $dok->caption }}</p>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400"><i class="fas fa-images text-3xl mb-3 block"></i>Belum ada dokumentasi</div>
        @endforelse
    </div>
</div>
@endsection
