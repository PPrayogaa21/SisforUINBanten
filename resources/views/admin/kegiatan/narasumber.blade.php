@extends('layouts.app')
@section('title', 'Narasumber - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Narasumber')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Kelola daftar narasumber kegiatan</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">← Kembali</a>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-amber-500 mr-2"></i>Tambah Narasumber</h3>
        <form method="POST" action="{{ route('admin.kegiatan.narasumber.add', $kegiatan) }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <select name="user_id" required class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                <option value="">Pilih pengguna...</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                @endforeach
            </select>
            <input type="text" name="topik_materi" placeholder="Topik materi (opsional)" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-medium hover:bg-amber-600 transition-colors">Tambah</button>
        </form>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-slate-100">
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Nama</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">NIP</th>
                <th class="text-left px-6 py-4 font-semibold text-slate-600">Topik Materi</th>
                <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($kegiatan->narasumber as $n)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $n->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $n->nip }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $n->pivot->topik_materi ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.kegiatan.narasumber.remove', [$kegiatan, $n]) }}" onsubmit="return confirm('Hapus narasumber ini?')">
                            @csrf @method('DELETE')
                            <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada narasumber</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
