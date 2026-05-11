@extends('layouts.app')
@section('title', 'Dashboard Peserta')
@section('page-title', 'Dashboard Peserta')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- =========================================================
         Welcome Header
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                Halo, <span class="text-emerald-600">{{ auth()->user()->biodata->nama_lengkap ?? auth()->user()->username }}</span>! 👋
            </h1>
            <p class="text-slate-500 mt-1 font-medium">Pantau seluruh kegiatan yang Anda ikuti di sini.</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm text-sm font-semibold text-slate-600">
            <i class="far fa-calendar text-emerald-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- =========================================================
         Stats Grid
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        {{-- Total Kegiatan --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-emerald-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 ring-4 ring-emerald-50">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-600 bg-emerald-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Total</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalKegiatanDiikuti }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Diikuti</p>
                </div>
            </div>
        </div>

        {{-- Kegiatan Aktif --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-50">
                        <i class="fas fa-bolt text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-blue-600 bg-blue-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Aktif</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $kegiatanAktif }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Sedang Berjalan</p>
                </div>
            </div>
        </div>

        {{-- Kegiatan Selesai --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-purple-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 ring-4 ring-purple-50">
                        <i class="fas fa-circle-check text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-purple-600 bg-purple-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">History</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $kegiatanSelesai }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Selesai</p>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================================================
         Kegiatan Terbaru — Timeline style
    ========================================================== --}}
    <div class="p-8 rounded-3xl bg-white border border-slate-200/60 shadow-sm">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Kegiatan Terbaru</h3>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Daftar kegiatan yang Anda ikuti</p>
            </div>
            <a href="{{ route('peserta.kegiatan.index') }}"
               class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition-colors">
                Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="relative">
            @if($kegiatanDiikuti->count() > 0)
                {{-- Timeline line --}}
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                <div class="space-y-6 relative">
                    @foreach($kegiatanDiikuti as $item)
                    <div class="relative pl-14 group">
                        {{-- Dot --}}
                        <div class="absolute left-4 top-1.5 w-4 h-4 bg-white border-4 {{
                            $item->status === 'ongoing'   ? 'border-blue-500'    :
                            ($item->status === 'completed' ? 'border-emerald-500' : 'border-slate-300')
                        }} rounded-full transition-all group-hover:scale-125 z-10"></div>

                        <a href="{{ route('peserta.kegiatan.show', $item) }}" class="block group/item">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    {{ $item->waktu_mulai ? $item->waktu_mulai->translatedFormat('d M Y, H:i') . ' WIB' : 'TBA' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black tracking-wider uppercase {{
                                    $item->status === 'published'  ? 'bg-sky-100 text-sky-700'     :
                                    ($item->status === 'ongoing'   ? 'bg-blue-100 text-blue-700'   :
                                    ($item->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'))
                                }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover/item:text-emerald-600 transition-colors line-clamp-1">
                                {{ $item->nama_kegiatan }}
                            </h4>
                            @if($item->tempat)
                            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                                <i class="fas fa-location-dot text-[10px]"></i> {{ $item->tempat }}
                            </p>
                            @endif
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                        <i class="fas fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">Belum ada kegiatan yang diikuti</p>
                    <p class="text-xs text-slate-400 mt-1">Kegiatan yang Anda ikuti akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
.animate-fade-in { animation: fadeInUp 0.4s ease both; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
