@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Beranda')

@section('content')
@php
    $ongoingUser = $kegiatanDiikuti->where('status', 'ongoing')->count();
    $completedUser = $kegiatanDiikuti->where('status', 'completed')->count();
    $priorityKegiatan = $kegiatanDiikuti->sortBy('waktu_mulai')->first();
    $jadwalHariIni = $kegiatanDiikuti->filter(fn($k) => $k->waktu_mulai && $k->waktu_mulai->isToday())->sortBy('waktu_mulai');
@endphp

<!-- Welcome Section -->
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-5">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Selamat datang, {{ explode(' ', $user->biodata->nama_lengkap ?? $user->username ?? 'User')[0] }} 👋</h2>
        <p class="text-slate-500 mt-2 text-[15px]">
            Anda memiliki <strong class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $totalKegiatanDiikuti }}</strong> kegiatan yang dapat dikelola hari ini.
        </p>
    </div>
    
    <!-- Quick Actions -->
    <div class="flex items-center gap-3">
        <a href="#jadwal" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <i class="fas fa-calendar-day mr-1.5 text-slate-400"></i> Jadwal Hari Ini
        </a>
        <a href="#semua-kegiatan" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition-all shadow-sm shadow-slate-800/20">
            <i class="fas fa-magnifying-glass mr-1.5 text-slate-400"></i> Cari Kegiatan
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-10">
    
    <!-- Left Column: Priority & Stats -->
    <div class="lg:col-span-2 space-y-6 lg:space-y-8">
        
        <!-- Statistik User -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-blue-200 transition-all">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-folder-open text-xl"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black text-slate-800">{{ $totalKegiatanDiikuti }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kegiatan Saya</span>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-amber-200 transition-all">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-spinner text-xl"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black text-slate-800">{{ $ongoingUser }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Berjalan</span>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-emerald-200 transition-all">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black text-slate-800">{{ $completedUser }}</span>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Selesai</span>
                </div>
            </div>
        </div>

        <!-- Highlight Priority Card -->
        <div>
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-star text-amber-400"></i> Prioritas Saat Ini
            </h3>
            
            @if($priorityKegiatan)
                @php
                    $rolePrio = $priorityKegiatan->isPeserta($user->id) ? 'Peserta' : 'Narasumber';
                    $routePrio = $priorityKegiatan->isPeserta($user->id) ? route('peserta.kegiatan.show', $priorityKegiatan) : route('narasumber.kegiatan.show', $priorityKegiatan);
                @endphp
                
                @if($priorityKegiatan->isPeserta($user->id))
                <div class="bg-slate-900 rounded-2xl p-6 relative overflow-hidden shadow-md group">
                    <div class="absolute top-0 right-0 p-8 opacity-5">
                        <i class="fas fa-user-check text-9xl"></i>
                    </div>
                @else
                <div class="bg-slate-900 rounded-2xl p-6 relative overflow-hidden shadow-md group">
                    <div class="absolute top-0 right-0 p-8 opacity-5">
                        <i class="fas fa-chalkboard-user text-9xl"></i>
                    </div>
                @endif
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="px-2.5 py-1 bg-white/10 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center">
                                    <i class="fas fa-bolt text-amber-400 mr-1.5"></i> Prioritas Utama
                                </span>
                                <span class="text-slate-400 text-xs font-medium flex items-center gap-1.5">
                                    <i class="fas fa-clock text-slate-500"></i> {{ $priorityKegiatan->waktu_mulai?->translatedFormat('d M Y, H:i') ?? 'Segera' }} WIB
                                </span>
                            </div>
                            
                            <h4 class="text-xl md:text-2xl font-bold text-white mb-3 leading-tight">{{ $priorityKegiatan->nama_kegiatan }}</h4>
                            
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
                        <a href="{{ $routePrio }}" class="w-full md:w-auto shrink-0 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors flex items-center justify-center gap-2 mt-2 md:mt-0">
                            Masuk Ruang <i class="fas fa-arrow-right"></i>
                        </a>
                        @else
                        <a href="{{ $routePrio }}" class="w-full md:w-auto shrink-0 bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors flex items-center justify-center gap-2 mt-2 md:mt-0">
                            Masuk Ruang <i class="fas fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 border border-slate-200 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-mug-hot text-2xl text-slate-400"></i>
                    </div>
                    <h4 class="text-slate-700 font-bold">Belum ada prioritas kegiatan</h4>
                    <p class="text-slate-500 text-sm mt-1">Anda sudah menyelesaikan semua kegiatan yang ditugaskan.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Right Column: Timeline Hari Ini -->
    <div class="lg:col-span-1" id="jadwal">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-day text-slate-400"></i> Jadwal Terdekat
        </h3>
        
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-[calc(100%-2.5rem)] flex flex-col">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center shrink-0">
                <span class="font-bold text-slate-700">Hari Ini</span>
                <span class="text-xs font-bold bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-slate-600 shadow-sm">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            
            <div class="p-5 flex-1 overflow-y-auto">
                @if($jadwalHariIni->count() > 0)
                    <div class="relative space-y-0 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-slate-200 before:via-slate-200 before:to-transparent">
                        @foreach($jadwalHariIni as $jdwl)
                            <div class="relative flex items-start justify-between mb-6 last:mb-0 group">
                                <!-- Icon -->
                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-slate-100 text-slate-500 shadow-sm shrink-0 z-10 transition-colors {{ $jdwl->isPeserta($user->id) ? 'group-hover:bg-emerald-100 group-hover:text-emerald-500' : 'group-hover:bg-blue-100 group-hover:text-blue-500' }}">
                                    <i class="fas fa-clock text-xs"></i>
                                </div>
                                <!-- Content -->
                                <div class="w-[calc(100%-3.5rem)] pt-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="font-bold text-slate-800 text-sm">{{ $jdwl->waktu_mulai->format('H:i') }} WIB</div>
                                    </div>
                                    <div class="text-slate-600 text-sm font-medium leading-snug mb-2">{{ $jdwl->nama_kegiatan }}</div>
                                    <div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $jdwl->isPeserta($user->id) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                            {{ $jdwl->isPeserta($user->id) ? 'Peserta' : 'Narasumber' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center py-10">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                            <i class="fas fa-bed text-2xl text-slate-300"></i>
                        </div>
                        <h4 class="text-slate-700 font-semibold mb-1">Jadwal Kosong</h4>
                        <p class="text-slate-500 text-sm">Tidak ada jadwal kegiatan untuk hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Daftar Semua Kegiatan -->
<div id="semua-kegiatan" class="pt-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Eksplorasi Kegiatan</h3>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh kegiatan yang tersedia di sistem.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($kegiatan as $item)
            @php
                $isPeserta = $item->isPeserta($user->id);
                $isNarasumber = $item->isNarasumber($user->id);
                $isSelesai = $item->status === 'completed';
            @endphp
            
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col group">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                {{ $item->jenis ?? 'Seminar' }}
                            </span>
                            @if($isSelesai)
                                <span class="px-3 py-1 bg-slate-50 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                    <i class="fas fa-check mr-1"></i> Selesai
                                </span>
                            @endif
                        </div>
                        
                        @if($isPeserta)
                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0" title="Anda sebagai Peserta">
                                <i class="fas fa-user-check"></i>
                            </div>
                        @elseif($isNarasumber)
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0" title="Anda sebagai Narasumber">
                                <i class="fas fa-chalkboard-user"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="font-bold text-slate-800 text-lg mb-3 line-clamp-2 leading-snug group-hover:text-slate-900 transition-colors">{{ $item->nama_kegiatan }}</h4>
                    
                    <div class="space-y-2 mt-4 text-sm text-slate-500">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="fas fa-calendar-day text-xs"></i>
                            </div>
                            <span class="font-medium">{{ $item->waktu_mulai?->translatedFormat('d M Y, H:i') ?? 'TBA' }} WIB</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="fas fa-location-dot text-xs"></i>
                            </div>
                            <span class="truncate font-medium">{{ $item->tempat ?? 'TBA' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 mt-auto">
                    @if($isPeserta)
                        <a href="{{ route('peserta.kegiatan.show', $item) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-emerald-600/20">
                            Masuk sebagai Peserta <i class="fas fa-arrow-right"></i>
                        </a>
                    @elseif($isNarasumber)
                        <a href="{{ route('narasumber.kegiatan.show', $item) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-blue-600/20">
                            Masuk sebagai Narasumber <i class="fas fa-arrow-right"></i>
                        </a>
                    @elseif(!$isSelesai)
                        <!-- Jika ada fitur daftar mandiri, ganti disabled button ini. Sementara sesuai request: belum terdaftar -> disabled -->
                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-400 rounded-xl text-sm font-bold cursor-not-allowed">
                            <i class="fas fa-lock text-xs"></i> Belum Terdaftar
                        </button>
                    @else
                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-100 border border-slate-200 text-slate-500 rounded-xl text-sm font-bold cursor-not-allowed">
                            <i class="fas fa-check-double text-xs"></i> Kegiatan Selesai
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-16 text-center shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-4xl text-slate-300"></i>
                </div>
                <h4 class="text-slate-800 font-bold text-xl mb-2">Belum ada kegiatan</h4>
                <p class="text-slate-500 max-w-md mx-auto">Sistem belum memiliki data kegiatan apa pun. Kegiatan yang ditambahkan oleh Admin akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
/* Smooth scroll behavior for internal links */
html {
    scroll-behavior: smooth;
}
</style>

@endsection