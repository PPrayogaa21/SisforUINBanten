@extends('layouts.app')
@section('title', 'Narasumber - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Narasumber')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 italic">Manajemen narasumber dan topik materi kegiatan</p>
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
        <!-- Sidebar: Form Tambah -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden sticky top-6">
                <div class="p-6 bg-gradient-to-br from-amber-500 to-orange-600 text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Tambah Narasumber
                    </h3>
                    <p class="text-xs text-amber-100 mt-1">Pilih dari user yang sudah terdaftar</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.narasumber.add', $kegiatan) }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari & Pilih User</label>
                            <div class="space-y-3">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" id="searchUser" placeholder="Ketik nama / NIP..." 
                                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 bg-slate-50 transition-all">
                                </div>
                                <select name="user_id" id="userSelect" required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Narasumber --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->biodata->nama_lengkap ?? $user->username }} ({{ $user->biodata->nip ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Topik Materi</label>
                            <textarea name="topik_materi" rows="3" placeholder="Masukkan topik materi yang dibawakan..." 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all bg-slate-50 resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-amber-500 text-white text-sm font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30 active:scale-[0.98]">
                            Tambah Narasumber
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main: Table Daftar -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('admin.kegiatan.narasumber.cetak-pdf', $kegiatan) }}" id="formCetakPdf" target="_blank">
                    @csrf
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800">Daftar Narasumber</h3>
                            <p class="text-xs text-slate-500">Terdapat {{ count($kegiatan->narasumber) }} narasumber terdaftar</p>
                        </div>
                        <button type="submit" id="btnCetakPdf" 
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 text-sm font-bold cursor-not-allowed transition-all border border-slate-200" disabled>
                            <i class="fas fa-file-pdf"></i> Cetak Biodata
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 w-12 text-center">
                                        <input type="checkbox" id="selectAllNarasumber" class="rounded-md border-slate-300 text-amber-500 focus:ring-amber-500 w-4 h-4">
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Materi</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($kegiatan->narasumber as $n)
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="narasumber_ids[]" value="{{ $n->id }}" class="narasumber-checkbox rounded-md border-slate-300 text-amber-500 focus:ring-amber-500 w-4 h-4">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($n->biodata->nama_lengkap ?? $n->username, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $n->biodata->nama_lengkap ?? $n->username }}</p>
                                                <p class="text-[11px] text-slate-500 font-mono">{{ $n->biodata->nip ?? 'NIP: -' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-[250px]">
                                            <p class="text-sm text-slate-600 italic line-clamp-2" title="{{ $n->pivot->topik_materi }}">
                                                {{ $n->pivot->topik_materi ?? 'Tidak ada topik' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" onclick="deleteNarasumber('{{ route('admin.kegiatan.narasumber.remove', [$kegiatan, $n]) }}')" 
                                            class="w-9 h-9 inline-flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-100">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                                <i class="fas fa-users-slash text-2xl text-slate-300"></i>
                                            </div>
                                            <p class="text-slate-500 font-medium">Belum ada narasumber</p>
                                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan narasumber melalui panel di samping</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden form for JS submission -->
    <form id="deleteNarasumberForm" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchUser');
        const userSelect = document.getElementById('userSelect');
        const selectAllNarasumber = document.getElementById('selectAllNarasumber');
        const narasumberCheckboxes = document.querySelectorAll('.narasumber-checkbox');
        const btnCetakPdf = document.getElementById('btnCetakPdf');
        
        if (searchInput && userSelect) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                
                Array.from(userSelect.options).forEach(option => {
                    if(option.value === '') return; // Skip placeholder
                    
                    const text = option.text.toLowerCase();
                    if(text.includes(term)) {
                        option.style.display = '';
                        option.hidden = false;
                    } else {
                        option.style.display = 'none';
                        option.hidden = true;
                    }
                });
                
                if(userSelect.selectedOptions[0]?.hidden) {
                    const firstVisible = Array.from(userSelect.options).find(o => !o.hidden && o.value !== '');
                    if(firstVisible) {
                        userSelect.value = firstVisible.value;
                    } else {
                        userSelect.value = '';
                    }
                }
            });
        }

        // PDF Checkbox Logic
        function updateBtnState() {
            const anyChecked = Array.from(narasumberCheckboxes).some(cb => cb.checked);
            btnCetakPdf.disabled = !anyChecked;
            if(anyChecked) {
                btnCetakPdf.classList.remove('bg-blue-400', 'cursor-not-allowed');
                btnCetakPdf.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                btnCetakPdf.classList.add('bg-blue-400', 'cursor-not-allowed');
                btnCetakPdf.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }
        }

        if (selectAllNarasumber) {
            selectAllNarasumber.addEventListener('change', function() {
                narasumberCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBtnState();
            });
            
            narasumberCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(narasumberCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(narasumberCheckboxes).some(c => c.checked);
                    selectAllNarasumber.checked = allChecked;
                    selectAllNarasumber.indeterminate = someChecked && !allChecked;
                    updateBtnState();
                });
            });
        }
    });

    function deleteNarasumber(url) {
        if(confirm('Hapus narasumber ini dari kegiatan?')) {
            const form = document.getElementById('deleteNarasumberForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endpush
@endsection
