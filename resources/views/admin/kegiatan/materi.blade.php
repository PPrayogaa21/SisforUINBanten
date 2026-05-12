@extends('layouts.app')
@section('title', 'Materi - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Materi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 italic">Manajemen materi paparan dan bahan ajar kegiatan</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Upload Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden sticky top-6">
                <div class="p-6 bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-file-upload"></i> Upload Materi
                    </h3>
                    <p class="text-xs text-blue-100 mt-1">Format: PDF, Word, PPT, Excel, ZIP</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.materi.upload', $kegiatan) }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Materi</label>
                            <input type="text" name="judul" required placeholder="Masukkan judul materi..." 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih File</label>
                            <div class="relative group">
                                <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip" 
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 file:font-bold file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-white hover:border-blue-300 transition-all">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 active:scale-[0.98]">
                            Upload Materi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main: Materials List -->
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 gap-4">
                @forelse($kegiatan->materi as $m)
                <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl {{ in_array($m->file_type, ['pdf']) ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-500' }} flex items-center justify-center flex-shrink-0 text-xl shadow-inner">
                                <i class="fas fa-file-{{ in_array($m->file_type, ['pdf']) ? 'pdf' : (in_array($m->file_type, ['doc','docx']) ? 'word' : (in_array($m->file_type, ['ppt','pptx']) ? 'powerpoint' : 'lines')) }}"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-800 truncate" title="{{ $m->judul }}">{{ $m->judul }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">{{ strtoupper($m->file_type) }}</span>
                                    <span class="text-slate-300 text-[10px]">•</span>
                                    <span class="text-slate-500 text-[10px] font-medium">{{ $m->file_size_formatted }}</span>
                                    <span class="text-slate-300 text-[10px]">•</span>
                                    <span class="text-slate-500 text-[10px] font-medium italic truncate">Oleh: {{ $m->uploader->biodata->nama_lengkap ?? $m->uploader->username }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::disk('public')->url($m->file_path) }}" target="_blank" 
                                class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-all border border-transparent hover:border-blue-100" 
                                title="Download / Preview">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.kegiatan.materi.delete', [$kegiatan, $m]) }}" class="form-delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-all border border-transparent hover:border-red-100">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-circle-xmark text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Belum ada materi</h3>
                    <p class="text-sm text-slate-500 mt-1">Materi yang di-upload akan muncul di sini</p>
                </div>
                @endforelse
            </div>
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
                    title: 'Hapus Materi?',
                    text: "Materi ini akan dihapus secara permanen.",
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
