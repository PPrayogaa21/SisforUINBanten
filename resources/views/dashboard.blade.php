@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Beranda')

@section('content')
@php
    $ongoingUser   = $kegiatanDiikuti->where('status', 'ongoing')->count();
    $completedUser = $kegiatanDiikuti->where('status', 'completed')->count();
    $priorityKegiatan = $kegiatanDiikuti->whereIn('status', ['published', 'ongoing'])->sortBy('waktu_mulai')->first();
    $jadwalHariIni    = $kegiatanDiikuti->filter(fn($k) => $k->waktu_mulai && $k->waktu_mulai->isToday())->sortBy('waktu_mulai');
@endphp

<div class="space-y-8 animate-fade-in">

    {{-- =========================================================
         Welcome Header
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                Selamat datang, <span class="text-emerald-600">{{ explode(' ', $user->biodata->nama_lengkap ?? $user->username ?? 'User')[0] }}</span>! 👋
            </h1>
            <p class="text-slate-500 mt-1 font-medium">
                Anda memiliki
                <strong class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $totalKegiatanDiikuti }}</strong>
                kegiatan yang dapat dikelola hari ini.
            </p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm text-sm font-semibold text-slate-600">
            <i class="far fa-calendar text-emerald-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- =========================================================
         Stats Grid — premium card style matching admin
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        {{-- Card: Kegiatan Saya --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-50">
                        <i class="fas fa-folder-open text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-blue-600 bg-blue-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Total</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalKegiatanDiikuti) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Saya</p>
                </div>
            </div>
        </div>

        {{-- Card: Berjalan --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-amber-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 ring-4 ring-amber-50">
                        <i class="fas fa-rocket text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-amber-600 bg-amber-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Aktif</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($ongoingUser) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Sedang Berjalan</p>
                </div>
            </div>
        </div>

        {{-- Card: Selesai --}}
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-emerald-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 ring-4 ring-emerald-50">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-600 bg-emerald-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">History</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($completedUser) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Selesai</p>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================================================
         Main Content Grid: Priority + Schedule
    ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column: Priority Card --}}
        <div class="lg:col-span-2 flex flex-col gap-8">

            {{-- Section Title --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-star text-amber-500 text-sm"></i>
                </div>
                <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Prioritas Saat Ini</h2>
            </div>

            @if($priorityKegiatan)
                @php
                    $rolePrio  = $priorityKegiatan->isPeserta($user->id) ? 'Peserta' : 'Narasumber';
                    $routePrio = $priorityKegiatan->isPeserta($user->id)
                        ? route('peserta.kegiatan.show',   $priorityKegiatan)
                        : route('narasumber.kegiatan.show', $priorityKegiatan);
                    $bgIcon    = $priorityKegiatan->isPeserta($user->id) ? 'fa-user-check' : 'fa-chalkboard-user';
                @endphp

                {{-- Dark Banner with Glow --}}
                <div class="group relative p-8 rounded-3xl bg-slate-900 overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl transform group-hover:scale-125 transition-transform duration-700"></div>
                    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                    {{-- Watermark Icon --}}
                    <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                        <i class="fas {{ $bgIcon }} text-9xl text-white"></i>
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-[0.2em]">
                                    <i class="fas fa-bolt text-amber-400 mr-1"></i> Prioritas Utama
                                </span>
                                <span class="text-slate-400 text-xs font-medium flex items-center gap-1.5">
                                    <i class="fas fa-clock text-slate-500"></i>
                                    {{ $priorityKegiatan->waktu_mulai?->translatedFormat('d M Y, H:i') ?? 'Segera' }} WIB
                                </span>
                            </div>

                            <h3 class="text-xl md:text-2xl font-extrabold text-white tracking-tight mb-3 leading-tight">
                                {{ $priorityKegiatan->nama_kegiatan }}
                            </h3>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-slate-400">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-location-dot text-slate-500"></i>
                                    <span class="truncate max-w-[200px]">{{ $priorityKegiatan->tempat ?? 'TBA' }}</span>
                                </span>
                                <span class="hidden sm:block text-slate-600">•</span>
                                <span class="flex items-center gap-2">
                                    Peran Anda: <strong class="text-white">{{ $rolePrio }}</strong>
                                </span>
                            </div>
                        </div>

                        @if($priorityKegiatan->isPeserta($user->id))
                            <a href="{{ $routePrio }}"
                               class="shrink-0 inline-flex items-center gap-2 px-6 py-4 rounded-2xl bg-emerald-500 text-white font-black shadow-lg shadow-emerald-500/40 hover:bg-emerald-400 hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                                <i class="fas fa-arrow-right text-sm"></i>
                                <span>Masuk Ruang</span>
                            </a>
                        @else
                            <a href="{{ $routePrio }}"
                               class="shrink-0 inline-flex items-center gap-2 px-6 py-4 rounded-2xl bg-blue-600 text-white font-black shadow-lg shadow-blue-600/40 hover:bg-blue-500 hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                                <i class="fas fa-arrow-right text-sm"></i>
                                <span>Masuk Ruang</span>
                            </a>
                        @endif
                    </div>
                </div>

            @else
                <div class="p-8 rounded-3xl bg-white border border-slate-200/60 shadow-sm text-center flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                        <i class="fas fa-mug-hot text-2xl"></i>
                    </div>
                    <h4 class="text-slate-700 font-bold">Belum ada prioritas kegiatan</h4>
                    <p class="text-slate-500 text-sm">Anda sudah menyelesaikan semua kegiatan yang ditugaskan.</p>
                </div>
            @endif

        </div>

        {{-- Right Column: Timeline Hari Ini --}}
        <div class="p-8 rounded-3xl bg-white border border-slate-200/60 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Jadwal Terdekat</h3>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">Kegiatan Hari Ini</p>
                </div>
                <span class="text-xs font-bold bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl text-slate-600 shadow-sm">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>

            <div class="flex-1 relative">
                @if($jadwalHariIni->count() > 0)
                    {{-- Timeline vertical line --}}
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                    <div class="space-y-8 relative">
                        @foreach($jadwalHariIni as $jdwl)
                        <div class="relative pl-14 group">
                            {{-- Dot --}}
                            <div class="absolute left-4 top-1.5 w-4 h-4 bg-white border-4 {{ $jdwl->isPeserta($user->id) ? 'border-emerald-500' : 'border-blue-500' }} rounded-full transition-all group-hover:scale-125 z-10"></div>

                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    {{ $jdwl->waktu_mulai->format('H:i') }} WIB
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black tracking-wider uppercase {{ $jdwl->isPeserta($user->id) ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $jdwl->isPeserta($user->id) ? 'Peserta' : 'Narasumber' }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors line-clamp-2">
                                {{ $jdwl->nama_kegiatan }}
                            </h4>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                            <i class="fas fa-bed text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Jadwal Kosong</p>
                        <p class="text-xs text-slate-400 mt-1">Tidak ada jadwal kegiatan untuk hari ini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- =========================================================
         Eksplorasi Kegiatan
    ========================================================== --}}
    <div id="semua-kegiatan">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Eksplorasi Kegiatan</h2>
                <p class="text-sm font-medium text-slate-400 mt-0.5">Daftar seluruh kegiatan yang tersedia di sistem.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($kegiatan as $item)
                @php
                    $isPeserta    = $item->isPeserta($user->id);
                    $isNarasumber = $item->isNarasumber($user->id);
                    $isSelesai    = $item->status === 'completed';
                @endphp

                <div class="group relative overflow-hidden bg-white rounded-3xl border border-slate-200/60 shadow-sm {{ $isSelesai ? 'opacity-75 grayscale-[0.5] cursor-not-allowed' : 'hover:shadow-xl hover:shadow-slate-200/80 hover:-translate-y-1 transition-all duration-300' }} flex flex-col">
                    {{-- Top accent line --}}
                    <div class="h-1 w-full {{ $isSelesai ? 'bg-slate-300' : ($isPeserta ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : ($isNarasumber ? 'bg-gradient-to-r from-blue-400 to-cyan-500' : 'bg-gradient-to-r from-slate-200 to-slate-300')) }}"></div>

                    <div class="p-6 flex-1">
                        {{-- Header badges --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                    {{ $item->jenis ?? 'Seminar' }}
                                </span>
                                @if($isSelesai)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-amber-200 flex items-center gap-1">
                                        <i class="fas fa-check-double text-[9px]"></i> Selesai
                                    </span>
                                @endif
                            </div>

                            @if($isPeserta && !$isSelesai)
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0 ring-4 ring-emerald-50 shadow-sm" title="Anda sebagai Peserta">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            @elseif($isNarasumber && !$isSelesai)
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0 ring-4 ring-blue-50 shadow-sm" title="Anda sebagai Narasumber">
                                    <i class="fas fa-chalkboard-user"></i>
                                </div>
                            @endif
                        </div>

                        <h4 class="font-extrabold text-slate-800 text-lg mb-4 line-clamp-2 leading-snug {{ !$isSelesai ? 'group-hover:text-slate-900' : 'text-slate-500' }} transition-colors">
                            {{ $item->nama_kegiatan }}
                        </h4>

                        <div class="space-y-2.5 text-sm text-slate-500">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                    <i class="fas fa-calendar-day text-xs"></i>
                                </div>
                                <span class="font-medium">{{ $item->waktu_mulai?->translatedFormat('d M Y, H:i') ?? 'TBA' }} WIB</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                    <i class="fas fa-location-dot text-xs"></i>
                                </div>
                                <span class="truncate font-medium">{{ $item->tempat ?? 'TBA' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer action --}}
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50 mt-auto">
                        @if($isSelesai)
                            <div class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-100 border border-slate-200 text-slate-400 rounded-2xl text-sm font-bold">
                                <i class="fas fa-ban text-xs"></i> Akses Ditutup
                            </div>
                        @elseif($isPeserta)
                            <a href="{{ route('peserta.kegiatan.show', $item) }}"
                               class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-sm font-black transition-all shadow-sm shadow-emerald-600/20 hover:-translate-y-0.5">
                                Masuk sebagai Peserta <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @elseif($isNarasumber)
                            <a href="{{ route('narasumber.kegiatan.show', $item) }}"
                               class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-sm font-black transition-all shadow-sm shadow-blue-600/20 hover:-translate-y-0.5">
                                Masuk sebagai Narasumber <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <button disabled
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-400 rounded-2xl text-sm font-bold cursor-not-allowed">
                                <i class="fas fa-lock text-xs"></i> Belum Terdaftar
                            </button>
                        @endif
                    </div>
                </div>

            @empty
                <div class="col-span-full p-16 rounded-3xl bg-white border border-slate-200/60 shadow-sm text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-4xl text-slate-300"></i>
                    </div>
                    <h4 class="text-slate-800 font-bold text-xl mb-2">Belum ada kegiatan</h4>
                    <p class="text-slate-500 max-w-md mx-auto">Sistem belum memiliki data kegiatan apa pun. Kegiatan yang ditambahkan oleh Admin akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>

<style>
html { scroll-behavior: smooth; }

.animate-fade-in {
    animation: fadeInUp 0.4s ease both;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

@endsection