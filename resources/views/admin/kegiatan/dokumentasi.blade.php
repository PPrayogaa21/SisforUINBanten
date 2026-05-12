@extends('layouts.app')
@section('title', 'Dokumentasi - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Dokumentasi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 italic">Galeri foto dokumentasi dan visual kegiatan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-slate-200/50 rounded-2xl w-max overflow-x-auto no-scrollbar">
        <a href="{{ route('admin.kegiatan.peserta', $kegiatan) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kegiatan.peserta') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Peserta
        </a>
        <a href="{{ route('admin.kegiatan.narasumber', $kegiatan) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kegiatan.narasumber') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Narasumber
        </a>
        <a href="{{ route('admin.kegiatan.materi', $kegiatan) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kegiatan.materi') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Materi
        </a>
        <a href="{{ route('admin.kegiatan.dokumentasi', $kegiatan) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kegiatan.dokumentasi') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Foto
        </a>
        <a href="{{ route('admin.kegiatan.dokumen', $kegiatan) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kegiatan.dokumen') ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Dokumen
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Upload Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden sticky top-6">
                <div class="p-6 bg-gradient-to-br from-purple-500 to-indigo-600 text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-camera"></i> Upload Foto
                    </h3>
                    <p class="text-xs text-purple-100 mt-1">Dukung multiple upload (Image only)</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.dokumentasi.upload', $kegiatan) }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Caption (Opsional)</label>
                            <input type="text" name="caption" placeholder="Beri keterangan foto..." 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-slate-50 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Foto</label>
                            <div class="relative">
                                <input type="file" name="files[]" multiple required accept="image/*" 
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-purple-50 file:text-purple-600 file:font-bold file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-white hover:border-purple-300 transition-all">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2 italic">* Anda dapat memilih lebih dari satu foto sekaligus</p>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-purple-600 text-white text-sm font-bold hover:bg-purple-700 transition-all shadow-lg shadow-purple-500/30 active:scale-[0.98]">
                            Mulai Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main: Photo Grid -->
        <div class="lg:col-span-3">
            @if($kegiatan->dokumentasi->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($kegiatan->dokumentasi as $dok)
                <div class="group relative aspect-square rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm bg-slate-100">
                    <img src="{{ asset('storage/' . $dok->file_path) }}" alt="{{ $dok->caption }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-4">
                        @if($dok->caption)
                        <p class="text-white text-xs font-medium mb-3 line-clamp-2 leading-relaxed">{{ $dok->caption }}</p>
                        @endif
                        <div class="flex items-center gap-2">
                            <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="flex-1 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white text-center text-[11px] font-bold backdrop-blur-sm transition-all border border-white/10">
                                Lihat
                            </a>
                            <form method="POST" action="{{ route('admin.kegiatan.dokumentasi.delete', [$kegiatan, $dok]) }}" class="form-delete flex-shrink-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-red-500/80 hover:bg-red-600 text-white flex items-center justify-center backdrop-blur-sm transition-all border border-white/10">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-20 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-images text-4xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Galeri Masih Kosong</h3>
                <p class="text-slate-500 mt-1 max-w-xs mx-auto">Abadikan momen kegiatan dengan meng-upload foto dokumentasi melalui panel di samping.</p>
            </div>
            @endif
        </div>
    </div>
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
                    title: 'Hapus Foto?',
                    text: "Foto ini akan dihapus permanen dari galeri.",
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
