@extends('layouts.app')
@section('title', 'Kuesioner - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Kuesioner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Buat dan kelola kuesioner evaluasi</p>
        </div>
        <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm shadow-sm transition-all">
            <i class="fas fa-arrow-left text-slate-400"></i> Kembali
        </a>
    </div>

    <!-- Create Kuesioner -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-plus text-emerald-500 mr-2"></i>Buat Kuesioner Baru</h3>
        <form method="POST" action="{{ route('admin.kegiatan.kuesioner.store', $kegiatan) }}" class="space-y-4">
            @csrf
            <div class="flex gap-3">
                <input type="text" name="judul" required placeholder="Judul kuesioner (contoh: Evaluasi Kegiatan)" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">Buat</button>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="auto_generate" value="1" checked class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                <span class="text-xs text-slate-600 font-medium">Gunakan Template Evaluasi Standar (Otomatis memasukkan 32 pertanyaan sesuai format resmi)</span>
            </label>
        </form>
    </div>

    <!-- List Kuesioner -->
    @forelse($kegiatan->kuesioner as $q)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">{{ $q->judul }}</h3>
            <div class="flex items-center gap-2">
                @if($q->pertanyaan->count() < 20)
                <form method="POST" action="{{ route('admin.kuesioner.load-template', $q) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold hover:bg-emerald-100 transition-all border border-emerald-100 shadow-sm flex items-center gap-1">
                        <i class="fas fa-magic"></i> Isi Template Resmi
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.kuesioner.hasil', $q) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-blue-100 flex items-center gap-1"><i class="fas fa-chart-bar"></i> Hasil ({{ $q->responses->count() }})</a>
                <a href="{{ route('admin.kuesioner.cetak-blanko', $q) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all border border-slate-200/60 flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-print"></i> Cetak Blanko PDF
                </a>
                <form method="POST" action="{{ route('admin.kuesioner.destroy', $q) }}" class="form-delete">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-medium hover:bg-red-100">Hapus</button>
                </form>
            </div>
        </div>

        <!-- Pertanyaan (Grouped and Scrollable for UX) -->
        @php
            $groupedQuestions = $q->pertanyaan->sortBy('urutan')->groupBy(function($p) {
                return $p->opsi['kategori'] ?? 'Lainnya';
            });
        @endphp

        @if($q->pertanyaan->isNotEmpty())
        <div class="space-y-5 mb-6 border-t border-slate-100 pt-5 max-h-[550px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
            @foreach($groupedQuestions as $kategori => $items)
            <div class="bg-slate-50/60 border border-slate-100 rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h4 class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-folder-open text-emerald-500"></i> {{ $kategori }}
                    </h4>
                    <span class="px-2 py-0.5 rounded-full bg-white border border-slate-200 text-slate-600 text-[10px] font-bold">{{ $items->count() }} butir</span>
                </div>
                
                <div class="bg-white border border-slate-200/70 rounded-xl divide-y divide-slate-100 shadow-sm overflow-hidden">
                    @foreach($items as $p)
                    <div class="px-4 py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 transition-colors group">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-extrabold text-[9px] flex items-center justify-center shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <p class="text-sm font-medium text-slate-700 truncate" title="{{ $p->pertanyaan }}">{{ $p->pertanyaan }}</p>
                            <span class="px-2 py-0.5 rounded-md border text-[9px] font-black tracking-wide shrink-0 {{ $p->tipe === 'rating' ? 'bg-indigo-50 border-indigo-100 text-indigo-600' : 'bg-amber-50 border-amber-100 text-amber-700' }}">
                                {{ strtoupper($p->tipe) }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('admin.pertanyaan.delete', $p) }}" class="form-delete-item">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all shrink-0">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-slate-400 text-sm italic">Kuesioner ini belum memiliki pertanyaan. Silakan klik "Isi Template Resmi" atau tambahkan manual di bawah.</div>
        @endif

        <!-- Add Pertanyaan Form -->
        <div class="pt-5 border-t border-slate-100">
            <h4 class="text-xs font-bold text-slate-600 mb-3 flex items-center gap-1.5"><i class="fas fa-plus-circle text-slate-400"></i> Tambah Pertanyaan Kustom Tambahan</h4>
            <form method="POST" action="{{ route('admin.kuesioner.pertanyaan.add', $q) }}" class="flex flex-col md:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="pertanyaan" required placeholder="Ketik pertanyaan baru disini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                </div>
                <div class="flex gap-3 shrink-0">
                    <select name="tipe" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white font-medium text-slate-700 focus:ring-2 focus:ring-emerald-400/50 focus:outline-none cursor-pointer">
                        <option value="rating">⭐ Rating (1-5)</option>
                        <option value="text">📝 Teks / Saran</option>
                    </select>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold shadow-md transition-all flex items-center gap-2 active:scale-95">
                        <i class="fas fa-plus text-xs"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-slate-400">Belum ada kuesioner</div>
    @endforelse
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
                    title: 'Hapus Kuesioner?',
                    text: "Seluruh data respons kuesioner ini akan ikut terhapus.",
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

        const deleteItemForms = document.querySelectorAll('.form-delete-item');
        deleteItemForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Pertanyaan?',
                    text: "Pertanyaan ini akan dihapus dari kuesioner.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl font-sans',
                        confirmButton: 'rounded-xl px-4 py-2 font-bold text-xs',
                        cancelButton: 'rounded-xl px-4 py-2 font-bold text-xs'
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
