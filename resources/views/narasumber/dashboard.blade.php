@extends('layouts.app')
@section('title', 'Dashboard Narasumber')
@section('page-title', 'Dashboard Narasumber')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- =========================================================
         Welcome Header
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                Halo, <span class="text-blue-600">{{ auth()->user()->biodata->nama_lengkap ?? auth()->user()->username }}</span>! 👋
            </h1>
            <p class="text-slate-500 mt-1 font-medium">Kelola sesi dan materi kegiatan Anda dari sini.</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm text-sm font-semibold text-slate-600">
            <i class="far fa-calendar text-blue-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- =========================================================
         Stats Grid
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        {{-- Total Kegiatan --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-amber-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 ring-4 ring-amber-50">
                        <i class="fas fa-chalkboard-user text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-amber-600 bg-amber-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Total</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalKegiatan }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Total Kegiatan</p>
                </div>
            </div>
        </div>

        {{-- Kegiatan Aktif --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-50">
                        <i class="fas fa-rocket text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-blue-600 bg-blue-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Berlangsung</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $kegiatanAktif }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Aktif</p>
                </div>
            </div>
        </div>

        {{-- Total Materi --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-purple-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 ring-4 ring-purple-50">
                        <i class="fas fa-file-lines text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-purple-600 bg-purple-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Upload</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalMateri }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Materi Diupload</p>
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
                <p class="text-xs font-medium text-slate-400 mt-0.5">Sesi yang Anda isi sebagai Narasumber</p>
            </div>
            <a href="{{ route('narasumber.kegiatan.index') }}"
               class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
                Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="relative">
            @if($kegiatanDiisi->count() > 0)
                {{-- Timeline line --}}
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                <div class="space-y-6 relative">
                    @foreach($kegiatanDiisi as $item)
                    <div class="relative pl-14 group">
                        {{-- Dot --}}
                        <div class="absolute left-4 top-1.5 w-4 h-4 bg-white border-4 {{
                            $item->status === 'ongoing'   ? 'border-blue-500'    :
                            ($item->status === 'completed' ? 'border-emerald-500' : 'border-amber-400')
                        }} rounded-full transition-all group-hover:scale-125 z-10"></div>

                        <a href="{{ route('narasumber.kegiatan.show', $item) }}" class="block group/item">
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
                            <h4 class="text-sm font-bold text-slate-800 group-hover/item:text-blue-600 transition-colors line-clamp-1">
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
                    <p class="text-sm font-bold text-slate-400">Belum ada kegiatan</p>
                    <p class="text-xs text-slate-400 mt-1">Kegiatan yang Anda pandu akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- =========================================================
         Quick Action Banner
    ========================================================== --}}
    <div class="group relative p-8 rounded-3xl bg-slate-900 overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl transform group-hover:scale-125 transition-transform duration-700"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
            <i class="fas fa-chalkboard-user text-9xl text-white"></i>
        </div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-3">
                    Quick Access
                </span>
                <h3 class="text-xl md:text-2xl font-extrabold text-white tracking-tight">Kelola Materi Presentasi</h3>
                <p class="text-slate-400 mt-2 font-medium max-w-md leading-relaxed">Upload dan atur bahan ajar untuk setiap sesi kegiatan yang Anda pandu.</p>
            </div>
            <a href="{{ route('narasumber.kegiatan.index') }}"
               class="shrink-0 inline-flex items-center gap-2 px-6 py-4 rounded-2xl bg-blue-600 text-white font-black shadow-lg shadow-blue-600/40 hover:bg-blue-500 hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                <i class="fas fa-list text-sm"></i>
                <span>Lihat Kegiatan</span>
            </a>
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
