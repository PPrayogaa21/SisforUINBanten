@extends('layouts.app')
@section('title', 'Kelola Kegiatan')
@section('page-title', 'Kelola Kegiatan')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Kelola semua kegiatan luar kantor</p>
    </div>
    <a href="{{ route('admin.kegiatan.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium text-sm shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all">
        <i class="fas fa-plus mr-2"></i> Tambah Kegiatan
    </a>
</div>

<!-- Filters -->
<div class="p-4 rounded-2xl bg-white border border-slate-200/50 shadow-sm mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
        <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm text-slate-600">
            <option value="">Semua Status</option>
            @foreach(['draft','published','ongoing','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="jenis" class="px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm text-slate-600">
            <option value="">Semua Jenis</option>
            @foreach(['rapat','seminar','pelatihan','workshop','lainnya'] as $j)
                <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white text-sm font-medium hover:bg-slate-700 transition-colors">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        @if(request('search') || request('status') || request('jenis'))
        <a href="{{ route('admin.kegiatan.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-sm font-medium hover:bg-slate-200 transition-colors flex items-center justify-center">
            <i class="fas fa-undo mr-1"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Kegiatan</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Jenis</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Waktu</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Status</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Peserta</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($kegiatan as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.kegiatan.show', $item) }}" class="font-medium text-slate-800 hover:text-emerald-600 transition-colors">{{ $item->nama_kegiatan }}</a>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $item->tempat }}</p>
                    </td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $item->jenis_badge }}">{{ ucfirst($item->jenis) }}</span></td>
                    <td class="px-6 py-4 text-slate-500">{{ $item->waktu_mulai->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span></td>
                    <td class="px-6 py-4 text-slate-500">{{ $item->peserta->count() }} orang</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.kegiatan.show', $item) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.kegiatan.edit', $item) }}" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.kegiatan.destroy', $item) }}" class="inline form-delete">
                                @csrf @method('DELETE')
                                <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <i class="fas fa-calendar-xmark text-3xl mb-3 block text-slate-300"></i>
                        Belum ada kegiatan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kegiatan->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $kegiatan->withQueryString()->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Kegiatan?',
                    text: "Seluruh data terkait kegiatan ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl font-sans',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-sm',
                        cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>
@endpush
@endsection
