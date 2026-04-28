@extends('layouts.app')
@section('title', 'Peserta - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Peserta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Kelola daftar peserta kegiatan</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">← Kembali</a>
    </div>

    <!-- Add Peserta -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-emerald-500 mr-2"></i>Tambah Peserta</h3>
        <form method="POST" action="{{ route('admin.kegiatan.peserta.add', $kegiatan) }}" class="flex gap-3">
            @csrf
            <select name="user_id" required class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                <option value="">Pilih pengguna...</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600 transition-colors">Tambah</button>
        </form>
    </div>

    <!-- Daftar Peserta -->
    <div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-slate-100">
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Nama</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">NIP</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Kehadiran</th>
                <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($kegiatan->peserta as $p)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $p->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $p->nip }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.kegiatan.peserta.kehadiran', [$kegiatan, $p]) }}" class="inline">
                            @csrf @method('PATCH')
                            <select name="status_kehadiran" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none">
                                @foreach(['registered' => 'Terdaftar', 'hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir'] as $val => $label)
                                <option value="{{ $val }}" {{ $p->pivot->status_kehadiran == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.kegiatan.peserta.remove', [$kegiatan, $p]) }}" onsubmit="return confirm('Hapus peserta ini?')">
                            @csrf @method('DELETE')
                            <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada peserta</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
