@extends('layouts.app')
@section('title', 'Approval User')
@section('page-title', 'Approval User')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800">Daftar Akun Menunggu Persetujuan</h2>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 flex items-center gap-3">
        <i class="fas fa-check-circle text-lg"></i>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-lg"></i>
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/50">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama & Username</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Registrasi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($user->biodata->nama_lengkap ?? $user->username ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $user->biodata->nama_lengkap ?? $user->username ?? 'User' }}</p>
                                    <p class="text-xs text-slate-500">ID: {{ $user->username ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $user->email ?? $user->biodata->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Pending</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline-block form-approve">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition-all text-xs font-bold shadow-sm" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline-block form-reject">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all text-xs font-bold shadow-sm" title="Reject">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-sm">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="font-bold text-slate-600 text-base mb-1">Tidak ada request approval</p>
                            <p>Semua pendaftaran telah di-review.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/50 bg-slate-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const approveForms = document.querySelectorAll('.form-approve');
        approveForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Approve User?',
                    text: "User ini akan diaktifkan dan dapat login ke sistem.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Approve!',
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

        const rejectForms = document.querySelectorAll('.form-reject');
        rejectForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Reject User?',
                    text: "User ini akan ditolak dan tidak dapat login ke sistem.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Reject!',
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
