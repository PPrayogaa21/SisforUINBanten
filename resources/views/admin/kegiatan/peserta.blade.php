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
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden sticky top-6">
                <div class="p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Tambah Peserta
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">Daftarkan peserta ke kegiatan</p>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.kegiatan.peserta.add', $kegiatan) }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari & Pilih User</label>
                            <div class="space-y-3">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" id="searchUser" placeholder="Ketik nama / NIP..." 
                                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-slate-50 transition-all">
                                </div>
                                <select name="user_id" id="userSelect" required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none bg-white">
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->biodata->nama_lengkap ?? $user->username }} ({{ $user->biodata->nip ?? '-' }})</option>
                                    @endforeach
                                </select>
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
        <div class="lg:col-span-3">
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
                                @forelse($kegiatan->peserta as $p)
                                @php
                                    $statusKehadiran = $p->pivot->status_kehadiran;
                                    $badge = match($statusKehadiran) {
                                        'hadir'       => ['bg-emerald-100 text-emerald-700', 'fa-circle-check', 'Hadir'],
                                        'tidak_hadir' => ['bg-red-100 text-red-600', 'fa-circle-xmark', 'Tidak Hadir'],
                                        default       => ['bg-amber-100 text-amber-600', 'fa-clock', 'Belum Absen'],
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="peserta_ids[]" value="{{ $p->id }}" class="peserta-checkbox rounded-md border-slate-300 text-emerald-500 focus:ring-emerald-500 w-4 h-4">
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
