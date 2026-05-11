@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang, <span class="text-emerald-600">{{ auth()->user()->biodata->nama_lengkap ?? auth()->user()->username }}</span>! 👋</h1>
            <p class="text-slate-500 mt-1 font-medium">Berikut ringkasan statistik platform Anda hari ini.</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/60 shadow-sm text-sm font-semibold text-slate-600">
            <i class="far fa-calendar text-emerald-500"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Enhanced Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-emerald-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 ring-4 ring-emerald-50">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-600 bg-emerald-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Total Data</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalKegiatan) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Total Kegiatan</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-50">
                        <i class="fas fa-rocket text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-blue-600 bg-blue-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Berlangsung</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($kegiatanAktif) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Aktif</p>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-amber-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 ring-4 ring-amber-50">
                        <i class="fas fa-users-gear text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-amber-600 bg-amber-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">Terdaftar</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalPengguna) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Total Pengguna</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="group relative overflow-hidden p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-purple-50 rounded-full transition-transform group-hover:scale-110"></div>
            <div class="relative flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 ring-4 ring-purple-50">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                    <div class="text-[10px] font-bold text-purple-600 bg-purple-100/60 px-2.5 py-1 rounded-full tracking-wider uppercase">History</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($kegiatanSelesai) }}</h3>
                    <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-wide">Kegiatan Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Konten Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Chart visual & Aksi -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Kegiatan Chart -->
            <div class="p-8 rounded-3xl bg-white border border-slate-200/60 shadow-sm relative overflow-hidden flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Intensitas Kegiatan</h3>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">Tren Data 6 Bulan Terakhir</p>
                    </div>
                </div>
                
                <div class="flex-1 flex flex-col justify-between gap-5">
                    @php
                        $maxVal = collect($chartData)->max('jumlah') ?: 1; // avoid division by zero
                    @endphp
                    @foreach($chartData as $data)
                    @php
                        $percentage = ($data['jumlah'] / $maxVal) * 100;
                        $percentage = max($percentage, 2); // visual min
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold tracking-wide">
                            <span class="text-slate-600 uppercase">{{ $data['bulan'] }}</span>
                            <span class="text-slate-800">{{ $data['jumlah'] }} Kegiatan</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden relative">
                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full transition-all duration-1000 ease-out group shadow-lg shadow-emerald-500/20" 
                                 style="width: {{ $percentage }}%">
                                <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Banner Aksi Cepat -->
            <div class="group relative p-8 rounded-3xl bg-slate-900 overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl transform group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-3">Quick Action</span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-white tracking-tight">Buka Kegiatan Baru Sekarang</h3>
                        <p class="text-slate-400 mt-2 font-medium max-w-md leading-relaxed">Rancang dan publikasikan agenda kegiatan kantor Anda dengan alur kerja yang seamless.</p>
                    </div>
                    <a href="{{ route('admin.kegiatan.create') }}" 
                       class="shrink-0 inline-flex items-center gap-2 px-6 py-4 rounded-2xl bg-emerald-500 text-white font-black shadow-lg shadow-emerald-500/40 hover:bg-emerald-400 hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Buat Kegiatan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Timeline Terbaru -->
        <div class="p-8 rounded-3xl bg-white border border-slate-200/60 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Aktifitas Terbaru</h3>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">Log Pembaruan Kegiatan</p>
                </div>
                <a href="{{ route('admin.kegiatan.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition-colors">
                    Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="flex-1 relative">
                @if($kegiatanTerbaru->count() > 0)
                <!-- Timeline visual line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-100"></div>
                
                <div class="space-y-8 relative">
                    @foreach($kegiatanTerbaru as $item)
                    <div class="relative pl-14 group">
                        <!-- Dot Timeline -->
                        <div class="absolute left-4 top-1.5 w-4 h-4 bg-white border-4 {{ $item->status === 'ongoing' ? 'border-blue-500' : ($item->status === 'completed' ? 'border-emerald-500' : 'border-slate-300') }} rounded-full transition-all group-hover:scale-125 z-10"></div>
                        
                        <a href="{{ route('admin.kegiatan.show', $item) }}" class="block group/item">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->created_at->diffForHumans() }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black tracking-wider uppercase {{ 
                                    $item->status === 'published' ? 'bg-sky-100 text-sky-700' : 
                                    ($item->status === 'ongoing' ? 'bg-blue-100 text-blue-700' : 
                                    ($item->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600')) 
                                }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover/item:text-emerald-600 transition-colors line-clamp-1">{{ $item->nama_kegiatan }}</h4>
                            
                            <!-- Peserta Minis -->
                            <div class="mt-3 flex items-center">
                                <div class="flex -space-x-2 overflow-hidden">
                                    @forelse($item->peserta->take(4) as $p)
                                    <div class="inline-block h-6 w-6 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 ring-2 ring-white flex items-center justify-center text-[9px] font-bold text-slate-600 uppercase" title="{{ $p->biodata->nama_lengkap ?? $p->username }}">
                                        {{ strtoupper(substr($p->biodata->nama_lengkap ?? $p->username, 0, 1)) }}
                                    </div>
                                    @empty
                                    @endforelse
                                </div>
                                @if($item->peserta->count() > 0)
                                <span class="text-[10px] font-bold text-slate-400 ml-2">
                                    {{ $item->peserta->count() }} Peserta
                                </span>
                                @else
                                <span class="text-[10px] italic font-medium text-slate-400">
                                    Belum ada peserta
                                </span>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                        <i class="fas fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">Tidak ada aktifitas terkini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

