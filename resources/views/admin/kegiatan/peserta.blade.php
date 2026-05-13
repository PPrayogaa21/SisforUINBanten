@extends('layouts.app')
@section('title', 'Peserta - ' . $kegiatan->nama_kegiatan)
@section('page-title', 'Kelola Peserta')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 italic">Kelola daftar peserta dan monitor kehadiran</p>
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

    {{-- Summary Kehadiran --}}
    @php
        $totalPeserta = $kegiatan->peserta->count();
        $hadirCount   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'hadir')->count();
        $tidakHadir   = $kegiatan->peserta->filter(fn($p) => $p->pivot->status_kehadiran === 'tidak_hadir')->count();
        $belumAbsen   = $kegiatan->peserta->filter(fn($p) => !in_array($p->pivot->status_kehadiran, ['hadir','tidak_hadir']))->count();
    @endphp
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-full transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800">{{ $hadirCount }}</p>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Hadir</p>
            </div>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-500/5 rounded-bl-full transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-2xl bg-red-500 flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                <i class="fas fa-user-xmark text-xl"></i>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800">{{ $tidakHadir }}</p>
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest">Tidak Hadir</p>
            </div>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-slate-200/60 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 rounded-bl-full transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-800">{{ $belumAbsen }}</p>
                <p class="text-xs font-bold text-amber-600 uppercase tracking-widest">Belum Absen</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Form Tambah -->
        <div class="lg:col-span-1" style="overflow: visible !important;">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm sticky top-6" style="overflow: visible !important; z-index: 40;">
                <div class="p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-t-[23px]">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Tambah Peserta
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">Daftarkan peserta ke kegiatan</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.peserta.add', $kegiatan) }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari User</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-slate-400 text-sm"></i>
                                </div>
                                <input type="text" id="customSearchInput" placeholder="Ketik nama / NIP..." autocomplete="off" style="padding-left: 45px !important; height: 46px;"
                                    class="w-full pr-4 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-slate-50 shadow-sm transition-all placeholder:text-slate-400">
                                <input type="hidden" name="user_id" id="hiddenUserId" required>
                                
                                <ul id="customDropdown" style="z-index: 9999 !important; max-height: 220px !important;" class="absolute w-full mt-2 bg-white border border-slate-200/70 rounded-2xl shadow-2xl overflow-y-auto hidden divide-y divide-slate-100 backdrop-blur-xl">
                                    @foreach($users as $user)
                                    <li class="custom-option px-4 py-3 hover:bg-emerald-50/80 cursor-pointer transition-all flex items-center gap-3 group" 
                                        data-id="{{ $user->id }}" 
                                        data-text="{{ strtolower(($user->biodata->nama_lengkap ?? $user->username) . ' ' . ($user->biodata->nip ?? '')) }}">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 pointer-events-none group-hover:scale-110 transition-transform">
                                            {{ strtoupper(substr($user->biodata->nama_lengkap ?? $user->username ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="pointer-events-none flex-1 min-w-0">
                                            <p class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 transition-colors truncate">{{ $user->biodata->nama_lengkap ?? $user->username }}</p>
                                            <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">NIP: {{ $user->biodata->nip ?? '-' }}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                    <li id="noResult" class="px-4 py-6 text-center text-slate-500 hidden flex flex-col items-center gap-1">
                                        <i class="fas fa-user-slash text-xl text-slate-300"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider">Tidak ditemukan</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/30 active:scale-[0.98]">
                            Tambah Peserta
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main: Table Daftar -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Search & Filter Bar -->
            <div class="flex items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.kegiatan.peserta', $kegiatan) }}" class="relative w-full md:w-[450px]">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau username..." style="padding-left: 45px !important; height: 46px;"
                        class="w-full pr-[100px] rounded-2xl border border-slate-200/80 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white shadow-sm transition-all placeholder:text-slate-400">
                    
                    @if(request('search'))
                    <a href="{{ route('admin.kegiatan.peserta', $kegiatan) }}" class="absolute right-[85px] inset-y-0 flex items-center text-slate-300 hover:text-slate-500 px-2 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                    
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 h-[34px] flex items-center px-5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors shadow-sm">
                        Cari
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('admin.kegiatan.peserta.cetak-pdf', $kegiatan) }}" id="formCetakPdf" target="_blank">
                    @csrf
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800">Daftar Peserta</h3>
                            <p class="text-xs text-slate-500">Terdapat {{ count($kegiatan->peserta) }} peserta terdaftar</p>
                        </div>
                        <button type="submit" id="btnCetakPdf" 
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 text-sm font-bold cursor-not-allowed transition-all border border-slate-200" disabled>
                            <i class="fas fa-file-pdf"></i> Cetak Biodata Terpilih
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 w-12 text-center">
                                        <input type="checkbox" id="selectAllPeserta" class="rounded-md border-slate-300 text-emerald-500 focus:ring-emerald-500 w-4 h-4">
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama & Identitas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Kehadiran</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($pesertaList as $p)
                                @php
                                    $statusKehadiran = $p->pivot->status_kehadiran;
                                    $badge = match($statusKehadiran) {
                                        'hadir'       => ['bg-emerald-100 text-emerald-700', 'fa-circle-check', 'Hadir'],
                                        'tidak_hadir' => ['bg-red-100 text-red-600', 'fa-circle-xmark', 'Tidak Hadir'],
                                        default       => ['bg-amber-100 text-amber-600', 'fa-clock', 'Belum Absen'],
                                    };
                                    $canPrint = $statusKehadiran === 'hadir';
                                @endphp
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="peserta_ids[]" value="{{ $p->id }}" class="peserta-checkbox rounded-md border-slate-300 text-emerald-500 focus:ring-emerald-500 w-4 h-4 disabled:opacity-50 disabled:cursor-not-allowed" {{ $canPrint ? '' : 'disabled title=Belum_Hadir' }}>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($p->biodata->nama_lengkap ?? $p->username, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $p->biodata->nama_lengkap ?? $p->username }}</p>
                                                <p class="text-[11px] text-slate-500 font-mono">{{ $p->biodata->nip ?? 'NIP: -' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badge[0] }}">
                                            <i class="fas {{ $badge[1] }}"></i>
                                            {{ $badge[2] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <select onchange="updateKehadiran('{{ route('admin.kegiatan.peserta.kehadiran', [$kegiatan, $p]) }}', this.value)" 
                                                class="px-3 py-1.5 rounded-xl border border-slate-200 text-[11px] font-bold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-600 bg-slate-50 hover:bg-white transition-all outline-none">
                                                @foreach(['registered' => 'Terdaftar', 'hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir'] as $val => $label)
                                                <option value="{{ $val }}" {{ $statusKehadiran == $val ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" onclick="deletePeserta('{{ route('admin.kegiatan.peserta.remove', [$kegiatan, $p]) }}')" 
                                                class="w-8 h-8 inline-flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center text-slate-400">
                                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                                <i class="fas fa-users-slash text-2xl text-slate-300"></i>
                                            </div>
                                            <p class="font-medium">Belum ada peserta</p>
                                            <p class="text-xs mt-1">Gunakan panel di samping untuk menambah peserta</p>
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
        const searchInput = document.getElementById('customSearchInput');
        const hiddenInput = document.getElementById('hiddenUserId');
        const dropdown = document.getElementById('customDropdown');
        const options = document.querySelectorAll('.custom-option');
        const noResult = document.getElementById('noResult');
        
        const selectAllPeserta = document.getElementById('selectAllPeserta');
        const pesertaCheckboxes = document.querySelectorAll('.peserta-checkbox');
        const btnCetakPdf = document.getElementById('btnCetakPdf');
        
        if (searchInput && dropdown) {
            // Show dropdown on focus
            searchInput.addEventListener('focus', () => {
                dropdown.classList.remove('hidden');
                // Scroll to top when opened
                dropdown.scrollTop = 0;
            });

            // Hide dropdown on blur with delay to allow clicking
            searchInput.addEventListener('blur', () => {
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            });

            // Filter options
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                let hasVisible = false;
                
                options.forEach(opt => {
                    if (opt.dataset.text.includes(term)) {
                        opt.style.display = '';
                        hasVisible = true;
                    } else {
                        opt.style.display = 'none';
                    }
                });
                
                if (hasVisible) {
                    noResult.classList.add('hidden');
                } else {
                    noResult.classList.remove('hidden');
                }
            });

            // Handle selection
            options.forEach(opt => {
                opt.addEventListener('click', function() {
                    const name = this.querySelector('p.font-bold').innerText;
                    searchInput.value = name;
                    hiddenInput.value = this.dataset.id;
                    dropdown.classList.add('hidden');
                });
            });
        }

        // PDF Checkbox Logic
        function updateBtnState() {
            const anyChecked = Array.from(pesertaCheckboxes).some(cb => cb.checked && !cb.disabled);
            btnCetakPdf.disabled = !anyChecked;
            if(anyChecked) {
                btnCetakPdf.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'border', 'border-slate-200');
                btnCetakPdf.classList.add('bg-emerald-600', 'text-white', 'shadow-md', 'shadow-emerald-500/30', 'hover:bg-emerald-700');
            } else {
                btnCetakPdf.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'border', 'border-slate-200');
                btnCetakPdf.classList.remove('bg-emerald-600', 'text-white', 'shadow-md', 'shadow-emerald-500/30', 'hover:bg-emerald-700');
            }
        }

        if (selectAllPeserta) {
            selectAllPeserta.addEventListener('change', function() {
                pesertaCheckboxes.forEach(cb => {
                    if (!cb.disabled) {
                        cb.checked = this.checked;
                    }
                });
                updateBtnState();
            });
            
            pesertaCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const activeCheckboxes = Array.from(pesertaCheckboxes).filter(c => !c.disabled);
                    const allChecked = activeCheckboxes.every(c => c.checked);
                    const someChecked = activeCheckboxes.some(c => c.checked);
                    selectAllPeserta.checked = allChecked && activeCheckboxes.length > 0;
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
        Swal.fire({
            title: 'Hapus Peserta?',
            text: "Peserta ini akan dihapus dari daftar kegiatan.",
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
            if (result.isConfirmed) {
                const form = document.getElementById('deletePesertaForm');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection
