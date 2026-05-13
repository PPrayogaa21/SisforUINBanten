@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Data User')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800">Daftar User</h2>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-sm font-medium hover:bg-emerald-600 transition-colors flex items-center gap-2 shadow-lg shadow-emerald-500/20">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/50 shadow-sm flex gap-3 items-center">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau Username/NIP user..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-slate-700 bg-white">
        </div>
        <select name="role" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-slate-600 bg-white">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
        </select>
        <select name="account_status" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-slate-600 bg-white">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('account_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('account_status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('account_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white text-sm font-medium hover:bg-slate-900 transition-colors whitespace-nowrap">
            <i class="fas fa-search mr-2"></i> Filter
        </button>
        @if(request('search') || request('role') || request('account_status'))
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors whitespace-nowrap">
            <i class="fas fa-undo mr-2"></i> Reset
        </a>
        @endif
    </form>

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
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jabatan & Bagian</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($user->biodata->nama_lengkap ?? $user->username ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $user->biodata->nama_lengkap ?? $user->username ?? 'User' }}</p>
                                    <p class="text-xs text-slate-500">ID: {{ $user->username ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $user->biodata->nip ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-800">{{ $user->biodata->jabatan ?? '-' }}</p>
                            <p class="text-xs text-slate-500 max-w-[200px] truncate" title="{{ $user->biodata->bagian ?? '' }}">{{ $user->biodata->bagian ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $user->biodata->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->account_status === 'pending')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Pending</span>
                            @elseif($user->account_status === 'approved')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Approved</span>
                            @elseif($user->account_status === 'rejected')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-3">
                                @if($user->account_status === 'pending')
                                <a href="{{ route('admin.users.approval') }}" 
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all border border-amber-100" 
                                   title="Review Pendaftaran">
                                    <i class="fas fa-clock text-xs"></i>
                                </a>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-slate-100 hover:border-emerald-100" 
                                   title="Edit User">
                                    <i class="fas fa-pencil text-xs"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all border border-slate-100 hover:border-red-100" 
                                            title="Hapus User">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
                            <i class="fas fa-users text-4xl mb-3 text-slate-300 block"></i>
                            Belum ada data user.
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
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus User?',
                    text: "Akun ini akan dihapus secara permanen dari sistem.",
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
