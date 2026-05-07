@extends('layouts.app')
@section('title', 'Dokumen - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Dokumen')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 italic">Manajemen berkas dan dokumen resmi kegiatan</p>
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
                <div class="p-6 bg-gradient-to-br from-red-500 to-rose-600 text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-file-signature"></i> Upload Dokumen
                    </h3>
                    <p class="text-xs text-red-100 mt-1">Kirim dokumen resmi ke peserta/narasumber</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.dokumen.upload', $kegiatan) }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Informasi Dokumen</label>
                            <div class="space-y-3">
                                <input type="text" name="judul" required placeholder="Judul dokumen (cth: Surat Tugas...)" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 bg-slate-50 transition-all">
                                
                                <select name="jenis" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 bg-slate-50 transition-all appearance-none">
                                    <option value="surat_tugas">Surat Tugas</option>
                                    <option value="undangan">Undangan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Penerima</label>
                            <div class="border border-slate-200 rounded-2xl p-4 max-h-60 overflow-y-auto space-y-3 bg-slate-50/50 custom-scrollbar">
                                <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer group border border-transparent hover:border-slate-100">
                                    <input type="checkbox" id="selectAllPenerima" class="rounded-md border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 transition-all">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Semua User</span>
                                </label>

                                @if($narasumber->count() > 0)
                                    <div class="pt-2">
                                        <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer group border border-transparent hover:border-slate-100 mb-1">
                                            <input type="checkbox" id="selectAllNarasumber" class="rounded-md border-slate-300 text-amber-500 focus:ring-amber-500 w-4 h-4 transition-all">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Semua Narasumber</p>
                                        </label>
                                        <div class="ml-4 space-y-1">
                                            @foreach($narasumber as $n)
                                            <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer group border border-transparent hover:border-slate-100">
                                                <input type="checkbox" name="target_user_id[]" value="{{ $n->id }}" class="penerima-checkbox narasumber-checkbox rounded-md border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 transition-all">
                                                <span class="text-xs text-slate-600 font-medium">{{ $n->biodata->nama_lengkap ?? $n->username }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($peserta->count() > 0)
                                    <div class="pt-2 border-t border-slate-100 mt-2">
                                        <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer group border border-transparent hover:border-slate-100 mb-1">
                                            <input type="checkbox" id="selectAllPeserta" class="rounded-md border-slate-300 text-emerald-500 focus:ring-emerald-500 w-4 h-4 transition-all">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Semua Peserta</p>
                                        </label>
                                        <div class="ml-4 space-y-1">
                                            @foreach($peserta as $p)
                                            <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-pointer group border border-transparent hover:border-slate-100">
                                                <input type="checkbox" name="target_user_id[]" value="{{ $p->id }}" class="penerima-checkbox peserta-checkbox rounded-md border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 transition-all">
                                                <span class="text-xs text-slate-600 font-medium">{{ $p->biodata->nama_lengkap ?? $p->username }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Dokumen</label>
                            <input type="file" name="file" required accept=".pdf,.doc,.docx" 
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-red-50 file:text-red-600 file:font-bold file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-white hover:border-red-300 transition-all">
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-500/30 active:scale-[0.98]">
                            Kirim Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main: Document List -->
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 gap-4">
                @forelse($kegiatan->dokumen as $doc)
                <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 text-xl shadow-inner">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-800 truncate" title="{{ $doc->judul }}">{{ $doc->judul }}</h4>
                                <div class="flex flex-col gap-1 mt-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider">{{ ucfirst(str_replace('_', ' ', $doc->jenis)) }}</span>
                                        <span class="text-slate-300 text-[10px]">•</span>
                                        <span class="text-slate-500 text-[10px] font-medium">{{ $doc->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 italic">
                                        <i class="fas fa-paper-plane mr-1"></i> Dikirim ke: <span class="font-bold text-slate-700">{{ $doc->targetUser->biodata->nama_lengkap ?? $doc->targetUser->username }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::disk('public')->url($doc->file_path) }}" target="_blank" 
                                class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 flex items-center justify-center transition-all border border-transparent hover:border-emerald-100" 
                                title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.kegiatan.dokumen.download', [$kegiatan, $doc]) }}" target="_blank" 
                                class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-all border border-transparent hover:border-blue-100" 
                                title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.kegiatan.dokumen.delete', [$kegiatan, $doc]) }}" onsubmit="return confirm('Hapus dokumen ini?')">
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
                    <h3 class="font-bold text-slate-800">Belum ada dokumen</h3>
                    <p class="text-sm text-slate-500 mt-1">Dokumen resmi yang di-upload akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllPenerima');
        const selectNarasumber = document.getElementById('selectAllNarasumber');
        const selectPeserta = document.getElementById('selectAllPeserta');
        
        const allCheckboxes = document.querySelectorAll('.penerima-checkbox');
        const narasumberCheckboxes = document.querySelectorAll('.narasumber-checkbox');
        const pesertaCheckboxes = document.querySelectorAll('.peserta-checkbox');
        
        // Global Select All
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                allCheckboxes.forEach(cb => cb.checked = this.checked);
                if (selectNarasumber) selectNarasumber.checked = this.checked;
                if (selectPeserta) selectPeserta.checked = this.checked;
            });
        }

        // Select All Narasumber
        if (selectNarasumber) {
            selectNarasumber.addEventListener('change', function() {
                narasumberCheckboxes.forEach(cb => cb.checked = this.checked);
                updateGlobalCheckbox();
            });
        }

        // Select All Peserta
        if (selectPeserta) {
            selectPeserta.addEventListener('change', function() {
                pesertaCheckboxes.forEach(cb => cb.checked = this.checked);
                updateGlobalCheckbox();
            });
        }
        
        // Individual Checkboxes
        allCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateSubGroupCheckboxes();
                updateGlobalCheckbox();
            });
        });

        function updateSubGroupCheckboxes() {
            if (selectNarasumber) {
                const allNara = Array.from(narasumberCheckboxes).every(c => c.checked);
                const someNara = Array.from(narasumberCheckboxes).some(c => c.checked);
                selectNarasumber.checked = allNara;
                selectNarasumber.indeterminate = someNara && !allNara;
            }
            if (selectPeserta) {
                const allPeserta = Array.from(pesertaCheckboxes).every(c => c.checked);
                const somePeserta = Array.from(pesertaCheckboxes).some(c => c.checked);
                selectPeserta.checked = allPeserta;
                selectPeserta.indeterminate = somePeserta && !allPeserta;
            }
        }

        function updateGlobalCheckbox() {
            if (selectAll) {
                const allChecked = Array.from(allCheckboxes).every(c => c.checked);
                const someChecked = Array.from(allCheckboxes).some(c => c.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }
        }
    });
</script>
@endpush
@endsection
