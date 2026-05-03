@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- 🔥 STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center mb-4">
            <i class="fas fa-layer-group text-white"></i>
        </div>
        <p class="text-3xl font-bold">{{ $kegiatanSaya ?? $kegiatan->count() }}</p>
        <p class="text-sm text-slate-500">Total Kegiatan</p>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center mb-4">
            <i class="fas fa-bolt text-white"></i>
        </div>
        <p class="text-3xl font-bold">{{ $kegiatanAktif ?? '-' }}</p>
        <p class="text-sm text-slate-500">Aktif</p>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center mb-4">
            <i class="fas fa-check text-white"></i>
        </div>
        <p class="text-3xl font-bold">{{ $kegiatanSelesai ?? '-' }}</p>
        <p class="text-sm text-slate-500">Selesai</p>
    </div>

</div>


{{-- 🔥 LIST KEGIATAN --}}
<div class="space-y-3">

@foreach($kegiatan as $item)

<div class="p-4 rounded-xl border bg-white hover:shadow-md transition">

    <div class="flex justify-between items-center">

        <div>
            <p class="font-semibold text-slate-800">
                {{ $item->nama_kegiatan }}
            </p>
            <p class="text-xs text-slate-400">
                {{ $item->waktu_mulai?->format('d M Y H:i') }}
            </p>
        </div>

        {{-- STATUS ROLE --}}
        @if($item->isPeserta($user->id))
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                Peserta
            </span>

        @elseif($item->isNarasumber($user->id))
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                Narasumber
            </span>

        @else
            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">
                Locked
            </span>
        @endif

    </div>

    {{-- ACTION --}}
    <div class="mt-3">

        @if($item->isPeserta($user->id))
            <a href="{{ route('peserta.kegiatan.show', $item) }}"
               class="text-green-600 text-sm font-medium">
                Masuk sebagai Peserta
            </a>

        @elseif($item->isNarasumber($user->id))
            <a href="{{ route('narasumber.kegiatan.show', $item) }}"
               class="text-blue-600 text-sm font-medium">
                Masuk sebagai Narasumber
            </a>

        @else
            <span class="text-gray-400 text-sm">
                Anda tidak terdaftar di kegiatan ini
            </span>
        @endif

    </div>

</div>

@endforeach

</div>

@endsection