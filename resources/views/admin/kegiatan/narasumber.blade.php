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

    <div class="max-w-2xl">
        <!-- Tambah dari akun yang sudah ada -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-visible">
            <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-amber-500 mr-2"></i>Pilih Narasumber Terdaftar</h3>
            
            <form method="POST" action="{{ route('admin.kegiatan.narasumber.add', $kegiatan) }}" class="space-y-4">
                @csrf
                <div class="w-full relative">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Cari User / Narasumber</label>
                    <input type="text" id="searchUser" placeholder="Ketik nama atau NIP untuk mencari..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50 mb-2">
                    <select name="user_id" id="userSelect" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50">
                        <option value="">Pilih pengguna...</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->biodata->nama_lengkap ?? $user->username }} ({{ $user->biodata->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Topik Materi (Opsional)</label>
                    <input type="text" name="topik_materi" placeholder="Topik materi (opsional)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-500 text-white text-sm font-medium hover:bg-amber-600 transition-colors">Tambah ke Kegiatan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('admin.kegiatan.narasumber.cetak-pdf', $kegiatan) }}" id="formCetakPdf" target="_blank">
            @csrf
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-semibold text-slate-800">Daftar Narasumber</h3>
                <button type="submit" id="btnCetakPdf" class="px-4 py-2 rounded-xl bg-blue-500 text-white text-sm font-medium hover:bg-blue-600 transition-colors shadow-sm" disabled>
                    <i class="fas fa-print mr-2"></i> Cetak PDF Biodata
                </button>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="border-b border-slate-100 bg-slate-50/50">
                    <th class="text-left px-6 py-4 font-semibold text-slate-600 w-12">
                        <input type="checkbox" id="selectAllNarasumber" class="rounded text-amber-500 focus:ring-amber-500 w-4 h-4 border-slate-300">
                    </th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Nama</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">NIP</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Topik Materi</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatan->narasumber as $n)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="narasumber_ids[]" value="{{ $n->id }}" class="narasumber-checkbox rounded text-amber-500 focus:ring-amber-500 w-4 h-4 border-slate-300">
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $n->biodata->nama_lengkap ?? $n->username }}</td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $n->biodata->nip ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $n->pivot->topik_materi ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" onclick="deleteNarasumber('{{ route('admin.kegiatan.narasumber.remove', [$kegiatan, $n]) }}')" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400"><i class="fas fa-users text-3xl mb-3 block text-slate-300"></i>Belum ada narasumber</td></tr>
                    @endforelse
                </tbody>
            </table>
        </form>
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
