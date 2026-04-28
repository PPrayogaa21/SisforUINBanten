@extends('layouts.app')
@section('title', 'Kegiatan Saya')
@section('page-title', 'Kegiatan Saya')

@section('content')
<div class="space-y-4">
    @forelse($kegiatan as $item)
    <a href="{{ route('peserta.kegiatan.show', $item) }}" class="block p-5 rounded-2xl bg-white border border-slate-200/50 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jenis_badge }}">{{ ucfirst($item->jenis) }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span>
                </div>
                <h3 class="font-semibold text-slate-800">{{ $item->nama_kegiatan }}</h3>
                <div class="flex flex-wrap gap-4 mt-2 text-xs text-slate-500">
                    <span><i class="fas fa-calendar mr-1 text-emerald-500"></i>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }}</span>
                    <span><i class="fas fa-location-dot mr-1 text-emerald-500"></i>{{ $item->tempat }}</span>
                    <span><i class="fas fa-file-lines mr-1 text-emerald-500"></i>{{ $item->materi->count() }} materi</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-slate-300 mt-2"></i>
        </div>
    </a>
    @empty
    <div class="text-center py-16 text-slate-400">
        <i class="fas fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
        Belum ada kegiatan yang diikuti
    </div>
    @endforelse

    {{ $kegiatan->links() }}
</div>
@endsection
