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

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-visible">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-amber-500 mr-2"></i>Tambah Narasumber</h3>
        
        <form method="POST" action="{{ route('admin.kegiatan.narasumber.add', $kegiatan) }}" class="flex flex-col sm:flex-row gap-3 items-start">
            @csrf
            <div class="flex-1 w-full">
                <select name="user_id" id="userSelect" required class="w-full">
                    <option value="">Pilih pengguna...</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <input type="text" name="topik_materi" placeholder="Topik materi (opsional)" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control { border-radius: 0.75rem; border-color: #e2e8f0; padding: 0.625rem 1rem; font-size: 0.875rem; box-shadow: none; }
    .ts-control.focus { border-color: #fbbf24; box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.5); }
    .ts-dropdown { border-radius: 0.75rem; border-color: #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-top: 4px; }
    .ts-dropdown .option { padding: 0.5rem 1rem; font-size: 0.875rem; }
    .ts-dropdown .active { background-color: #fffbeb; color: #b45309; }
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
