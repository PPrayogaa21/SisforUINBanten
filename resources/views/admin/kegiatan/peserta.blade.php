@extends('layouts.app')
@section('title', 'Peserta - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Peserta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500">Kelola daftar peserta dan monitor kehadiran secara real-time</p>
        </div>
    <a href="{{ route('admin.kegiatan.show', $kegiatan) }}"
    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium shadow-sm transition">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>

        Kembali
    </a>
    </div>

    {{-- Summary Kehadiran --}}
    @php
        $totalPeserta = $kegiatan->peserta->count();
        $hadirCount   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'hadir')->count();
        $tidakHadir   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'tidak_hadir')->count();
        $belumAbsen   = $kegiatan->peserta->filter(fn($p) => !in_array($p->pivot->status_kehadiran, ['hadir','tidak_hadir']))->count();
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center">
                <i class="fas fa-user-check text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-700">{{ $hadirCount }}</p>
                <p class="text-xs text-emerald-600 font-medium">Hadir</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-400 flex items-center justify-center">
                <i class="fas fa-user-xmark text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600">{{ $tidakHadir }}</p>
                <p class="text-xs text-red-500 font-medium">Tidak Hadir</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $belumAbsen }}</p>
                <p class="text-xs text-amber-500 font-medium">Belum Absen</p>
            </div>
        </div>
    </div>

    <!-- Add Peserta -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-visible">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="fas fa-user-plus text-emerald-500 mr-2"></i>Tambah Peserta</h3>
        
        <form method="POST" action="{{ route('admin.kegiatan.peserta.add', $kegiatan) }}" class="flex flex-col sm:flex-row gap-3 items-start">
            @csrf
            <div class="flex-1 w-full relative">
                <input type="text" id="searchUser" placeholder="Ketik nama atau NIP untuk mencari..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50 mb-2">
                <select name="user_id" id="userSelect" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                    <option value="">Pilih pengguna...</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->biodata->nama_lengkap ?? $user->username }} ({{ $user->biodata->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-medium hover:bg-emerald-600 transition-colors sm:mt-[52px]">Tambah</button>
        </form>
    </div>

    <!-- Daftar Peserta -->
    <div class="rounded-2xl bg-white border border-slate-200/50 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('admin.kegiatan.peserta.cetak-pdf', $kegiatan) }}" id="formCetakPdf" target="_blank">
            @csrf
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-semibold text-slate-800">Daftar Peserta</h3>
                <button type="submit" id="btnCetakPdf" class="px-4 py-2 rounded-xl bg-blue-500 text-white text-sm font-medium hover:bg-blue-600 transition-colors shadow-sm" disabled>
                    <i class="fas fa-print mr-2"></i> Cetak PDF Biodata
                </button>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="border-b border-slate-100 bg-slate-50/50">
                    <th class="text-left px-6 py-4 font-semibold text-slate-600 w-12">
                        <input type="checkbox" id="selectAllPeserta" class="rounded text-emerald-500 focus:ring-emerald-500 w-4 h-4 border-slate-300">
                    </th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Nama</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">NIP</th>
                    <th class="text-left px-6 py-4 font-semibold text-slate-600">Status Kehadiran</th>
                    <th class="text-right px-6 py-4 font-semibold text-slate-600">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatan->peserta as $p)
                    @php
                        $statusKehadiran = $p->pivot->status_kehadiran;
                        $badge = match($statusKehadiran) {
                            'hadir'       => ['bg-emerald-100 text-emerald-700', 'fa-circle-check', 'Hadir'],
                            'tidak_hadir' => ['bg-red-100 text-red-600', 'fa-circle-xmark', 'Tidak Hadir'],
                            default       => ['bg-amber-100 text-amber-600', 'fa-clock', 'Belum Absen'],
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="peserta_ids[]" value="{{ $p->id }}" class="peserta-checkbox rounded text-emerald-500 focus:ring-emerald-500 w-4 h-4 border-slate-300">
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $p->biodata->nama_lengkap ?? $p->username }}</td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $p->biodata->nip ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Indikator Badge --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge[0] }}">
                                    <i class="fas {{ $badge[1] }} text-xs"></i>
                                    {{ $badge[2] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <!-- Update and Delete actions isolated -->
                            <div class="flex justify-end items-center gap-2">
                                <select onchange="updateKehadiran('{{ route('admin.kegiatan.peserta.kehadiran', [$kegiatan, $p]) }}', this.value)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none text-slate-600 bg-white">
                                    @foreach(['registered' => 'Terdaftar', 'hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir'] as $val => $label)
                                    <option value="{{ $val }}" {{ $statusKehadiran == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="deletePeserta('{{ route('admin.kegiatan.peserta.remove', [$kegiatan, $p]) }}')" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400"><i class="fas fa-users text-3xl mb-3 block text-slate-300"></i>Belum ada peserta</td></tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    <!-- Hidden forms for JS submission -->
    <form id="updateKehadiranForm" method="POST" class="hidden">
        @csrf @method('PATCH')
        <input type="hidden" name="status_kehadiran" id="updateKehadiranValue">
    </form>
    <form id="deletePesertaForm" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchUser');
        const userSelect = document.getElementById('userSelect');
        const selectAllPeserta = document.getElementById('selectAllPeserta');
        const pesertaCheckboxes = document.querySelectorAll('.peserta-checkbox');
        const btnCetakPdf = document.getElementById('btnCetakPdf');
        
        if (searchInput && userSelect) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                
                Array.from(userSelect.options).forEach(option => {
                    if(option.value === '') return;
                    
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
            const anyChecked = Array.from(pesertaCheckboxes).some(cb => cb.checked);
            btnCetakPdf.disabled = !anyChecked;
            if(anyChecked) {
                btnCetakPdf.classList.remove('bg-blue-400', 'cursor-not-allowed');
                btnCetakPdf.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                btnCetakPdf.classList.add('bg-blue-400', 'cursor-not-allowed');
                btnCetakPdf.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }
        }

        if (selectAllPeserta) {
            selectAllPeserta.addEventListener('change', function() {
                pesertaCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBtnState();
            });
            
            pesertaCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(pesertaCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(pesertaCheckboxes).some(c => c.checked);
                    selectAllPeserta.checked = allChecked;
                    selectAllPeserta.indeterminate = someChecked && !allChecked;
                    updateBtnState();
                });
            });
        }
    });

    function updateKehadiran(url, value) {
        const form = document.getElementById('updateKehadiranForm');
        form.action = url;
        document.getElementById('updateKehadiranValue').value = value;
        form.submit();
    }

    function deletePeserta(url) {
        if(confirm('Hapus peserta ini dari kegiatan?')) {
            const form = document.getElementById('deletePesertaForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endpush
@endsection
