@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-calendar-days text-white text-lg"></i>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Total</span>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $totalKegiatan }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Kegiatan</p>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-bolt text-white text-lg"></i>
            </div>
            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $kegiatanAktif }}</p>
        <p class="text-sm text-slate-500 mt-1">Kegiatan Aktif</p>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-users text-white text-lg"></i>
            </div>
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Pengguna</span>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $totalPengguna }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Pengguna</p>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/20">
                <i class="fas fa-check-circle text-white text-lg"></i>
            </div>
            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-slate-800">{{ $kegiatanSelesai }}</p>
        <p class="text-sm text-slate-500 mt-1">Kegiatan Selesai</p>
    </div>
</div>

<!-- Chart + Recent -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Chart -->
    <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-slate-800">Kegiatan per Bulan</h3>
            <span class="text-xs text-slate-400">6 bulan terakhir</span>
        </div>
        <div class="space-y-3">
            @foreach($chartData as $data)
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-500 w-20 text-right">{{ $data['bulan'] }}</span>
                <div class="flex-1 h-8 bg-slate-100 rounded-lg overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-lg flex items-center justify-end pr-2 transition-all duration-500"
                        style="width: {{ $data['jumlah'] > 0 ? max(10, min(100, $data['jumlah'] * 20)) : 0 }}%">
                        @if($data['jumlah'] > 0)
                        <span class="text-xs font-semibold text-white">{{ $data['jumlah'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-slate-800">Kegiatan Terbaru</h3>
            <a href="{{ route('admin.kegiatan.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">Lihat Semua →</a>
        </div>
        <div class="space-y-4">
            @forelse($kegiatanTerbaru as $item)
            <a href="{{ route('admin.kegiatan.show', $item) }}" class="block p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <p class="text-sm font-medium text-slate-800 truncate">{{ $item->nama_kegiatan }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span>
                    <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Belum ada kegiatan</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="mt-6 p-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold">Buat Kegiatan Baru</h3>
            <p class="text-emerald-100 text-sm">Tambahkan kegiatan luar kantor baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.kegiatan.create') }}" class="px-6 py-3 rounded-xl bg-white text-emerald-600 font-semibold hover:bg-emerald-50 transition-colors shadow-lg">
            <i class="fas fa-plus mr-2"></i> Buat Kegiatan
        </a>
    </div>
</div>
@endsection
