@extends('layouts.app')
@section('title', 'Dokumen - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Dokumen')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Upload surat tugas, undangan, dan dokumen resmi</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="text-sm text-emerald-600 font-medium">← Kembali</a>
    </div>

    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-file-upload text-red-500 mr-2"></i>Upload Dokumen</h3>
        <form method="POST" action="{{ route('admin.kegiatan.dokumen.upload', $kegiatan) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul Dokumen</label>
                    <input type="text" name="judul" required placeholder="Judul dokumen" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-400/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Dokumen</label>
                    <select name="jenis" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-400/50">
                        <option value="surat_tugas">Surat Tugas</option>
                        <option value="undangan">Undangan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Penerima Dokumen</label>
                <div class="border border-slate-200 rounded-xl p-4 max-h-48 overflow-y-auto space-y-2 bg-slate-50">
                    <div class="mb-3 pb-3 border-b border-slate-200">
                        <label class="flex items-center gap-2 cursor-pointer w-max">
                            <input type="checkbox" id="selectAllPenerima" class="rounded text-red-500 focus:ring-red-500 w-4 h-4 border-slate-300">
                            <span class="text-sm font-semibold text-slate-700">Pilih Semua</span>
                        </label>
                    </div>

                    @if($narasumber->count() > 0)
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-2">Narasumber</p>
                        @foreach($narasumber as $n)
                        <label class="flex items-center gap-2 cursor-pointer pl-2 w-max">
                            <input type="checkbox" name="target_user_id[]" value="{{ $n->id }}" class="penerima-checkbox rounded text-red-500 focus:ring-red-500 w-4 h-4 border-slate-300">
                            <span class="text-sm text-slate-600">{{ $n->biodata->nama_lengkap ?? $n->username }}</span>
                        </label>
                        @endforeach
                    @endif

                    @if($peserta->count() > 0)
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-4">Peserta</p>
                        @foreach($peserta as $p)
                        <label class="flex items-center gap-2 cursor-pointer pl-2 w-max">
                            <input type="checkbox" name="target_user_id[]" value="{{ $p->id }}" class="penerima-checkbox rounded text-red-500 focus:ring-red-500 w-4 h-4 border-slate-300">
                            <span class="text-sm text-slate-600">{{ $p->biodata->nama_lengkap ?? $p->username }}</span>
                        </label>
                        @endforeach
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">File Dokumen (PDF, Word)</label>
                <input type="file" name="file" required accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-red-50 file:text-red-600 file:font-medium file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-white">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition-colors shadow-sm">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Dokumen
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($kegiatan->dokumen as $doc)
        <div class="p-4 rounded-xl bg-white border border-slate-200/50 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="fas fa-file-pdf text-red-500"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-medium text-sm text-slate-800 truncate" title="{{ $doc->judul }}">{{ $doc->judul }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ ucfirst(str_replace('_', ' ', $doc->jenis)) }}</p>
            </div>
            <div class="flex-shrink-0 flex gap-1">
                <a href="{{ route('admin.kegiatan.dokumen.download', [$kegiatan, $doc]) }}" target="_blank"
                   class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Download">
                    <i class="fas fa-download text-sm"></i>
                </a>
                <form method="POST" action="{{ route('admin.kegiatan.dokumen.delete', [$kegiatan, $doc]) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                    @csrf @method('DELETE')
                    <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400">
            <i class="fas fa-file-circle-xmark text-3xl mb-3 block text-slate-300"></i>
            Belum ada dokumen
        </div>
        @endforelse
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllPenerima');
        const checkboxes = document.querySelectorAll('.penerima-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const someChecked = Array.from(checkboxes).some(c => c.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = someChecked && !allChecked;
                });
            });
        }
    });
</script>
@endpush
@endsection
