@extends('layouts.app')
@section('title', 'Kuesioner - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Kuesioner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Buat dan kelola kuesioner evaluasi</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 font-medium">← Kembali</a>
    </div>

    <!-- Create Kuesioner -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-plus text-emerald-500 mr-2"></i>Buat Kuesioner Baru</h3>
        <form method="POST" action="{{ route('admin.kegiatan.kuesioner.store', $kegiatan) }}" class="flex gap-3">
            @csrf
            <input type="text" name="judul" required placeholder="Judul kuesioner" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600">Buat</button>
        </form>
    </div>

    <!-- List Kuesioner -->
    @forelse($kegiatan->kuesioner as $q)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">{{ $q->judul }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.kuesioner.hasil', $q) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-blue-100">Hasil ({{ $q->responses->count() }})</a>
                <form method="POST" action="{{ route('admin.kuesioner.destroy', $q) }}" onsubmit="return confirm('Hapus kuesioner ini?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-medium hover:bg-red-100">Hapus</button>
                </form>
            </div>
        </div>

        <!-- Pertanyaan -->
        <div class="space-y-2 mb-4">
            @foreach($q->pertanyaan as $i => $p)
            <div class="p-3 rounded-lg bg-slate-50 flex items-center justify-between">
                <span class="text-sm text-slate-700">{{ $i + 1 }}. {{ $p->pertanyaan }} <span class="text-xs text-slate-400">({{ $p->tipe }})</span></span>
                <form method="POST" action="{{ route('admin.pertanyaan.delete', $p) }}">
                    @csrf @method('DELETE')
                    <button class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Add Pertanyaan -->
        <form method="POST" action="{{ route('admin.kuesioner.pertanyaan.add', $q) }}" class="flex flex-col sm:flex-row gap-2 pt-4 border-t border-slate-100">
            @csrf
            <input type="text" name="pertanyaan" required placeholder="Tambah pertanyaan..." class="flex-1 px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <select name="tipe" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                <option value="rating">Rating (1-5)</option>
                <option value="text">Teks</option>
                <option value="pilihan_ganda">Pilihan Ganda</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-medium hover:bg-slate-700">Tambah</button>
        </form>
    </div>
    @empty
    <div class="text-center py-12 text-slate-400">Belum ada kuesioner</div>
    @endforelse
</div>
@endsection
