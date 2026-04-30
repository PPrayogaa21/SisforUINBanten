@extends('layouts.app')
@section('title', 'Peserta - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Peserta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Kelola daftar peserta dan monitor kehadiran secara real-time</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">← Kembali</a>
    </div>

    {{-- Summary Kehadiran --}}
    @php
        $totalPeserta = $kegiatan->peserta->count();
        $hadirCount   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'hadir')->count();
        $tidakHadir   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'tidak_hadir')->count();
        $belumAbsen   = $kegiatan->peserta->filter(fn($p) => !in_array($p->pivot->status_kehadiran, ['hadir','tidak_hadir']))->count();
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center">
                <i class="fas fa-user-check text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-700">{{ $hadirCount }}</p>
                <p class="text-xs text-emerald-600 font-medium">Hadir</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-400 flex items-center justify-center">
                <i class="fas fa-user-xmark text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600">{{ $tidakHadir }}</p>
                <p class="text-xs text-red-500 font-medium">Tidak Hadir</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $belumAbsen }}</p>
                <p class="text-xs text-amber-500 font-medium">Belum Absen</p>
            </div>
        </div>
    </div>

    <!-- Add Peserta -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-visible">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-emerald-500 mr-2"></i>Tambah Peserta</h3>
        
        <form method="POST" action="{{ route('admin.kegiatan.peserta.add', $kegiatan) }}" class="flex gap-3 items-start">
            @csrf
            <div class="flex-1">
                <select name="user_id" id="userSelect" required class="w-full">
                    <option value="">Pilih pengguna...</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600 transition-colors">Tambah</button>
        </form>
    </div>

    <!-- Daftar Peserta -->
    <div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-slate-100 bg-slate-50/50">
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Nama</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">NIP</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Status Kehadiran</th>
                <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($kegiatan->peserta as $p)
                @php
                    $statusKehadiran = $p->pivot->status_kehadiran;
                    $badge = match($statusKehadiran) {
                        'hadir'       => ['bg-emerald-100 text-emerald-700', 'fa-circle-check', 'Hadir'],
                        'tidak_hadir' => ['bg-red-100 text-red-600', 'fa-circle-xmark', 'Tidak Hadir'],
                        default       => ['bg-amber-100 text-amber-600', 'fa-clock', 'Belum Absen'],
                    };
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $p->name }}</td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $p->nip }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Indikator Badge --}}
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge[0] }}">
                                <i class="fas {{ $badge[1] }} text-xs"></i>
                                {{ $badge[2] }}
                            </span>
                            {{-- Dropdown Update Admin --}}
                            <form method="POST" action="{{ route('admin.kegiatan.peserta.kehadiran', [$kegiatan, $p]) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="status_kehadiran" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none text-slate-600 bg-white">
                                    @foreach(['registered' => 'Terdaftar', 'hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir'] as $val => $label)
                                    <option value="{{ $val }}" {{ $statusKehadiran == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.kegiatan.peserta.remove', [$kegiatan, $p]) }}" onsubmit="return confirm('Hapus peserta ini?')">
                            @csrf @method('DELETE')
                            <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400"><i class="fas fa-users text-3xl mb-3 block text-slate-300"></i>Belum ada peserta</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control { border-radius: 0.75rem; border-color: #e2e8f0; padding: 0.625rem 1rem; font-size: 0.875rem; box-shadow: none; }
    .ts-control.focus { border-color: #34d399; box-shadow: 0 0 0 2px rgba(52, 211, 153, 0.5); }
    .ts-dropdown { border-radius: 0.75rem; border-color: #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-top: 4px; }
    .ts-dropdown .option { padding: 0.5rem 1rem; font-size: 0.875rem; }
    .ts-dropdown .active { background-color: #ecfdf5; color: #065f46; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect("#userSelect", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "Ketik nama atau NIP untuk mencari..."
        });
    });
</script>
@endpush
@endsection
