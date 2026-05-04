@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/50 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Informasi User</h2>
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $user->biodata->nama_lengkap ?? '') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors @error('nama') border-red-500 @enderror">
                        @error('nama') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Username / ID <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors @error('username') border-red-500 @enderror">
                        @error('username') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">NIP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $user->biodata->nip ?? '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors @error('nip') border-red-500 @enderror">
                        @error('nip') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Jabatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->biodata->jabatan ?? '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Bagian <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="bagian" value="{{ old('bagian', $user->biodata->bagian ?? '') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->biodata->email ?? '') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Role Akses <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Bisa jadi Peserta/Narasumber)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-6">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"><i class="fas fa-lock mr-1"></i> Ubah Password</p>
                    <p class="text-xs text-slate-400 mb-4">Kosongkan jika tidak ingin mengubah password.</p>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Password Baru</label>
                            <input type="password" name="password" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors @error('password') border-red-500 @enderror"
                                placeholder="Biarkan kosong jika tidak diubah">
                            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors"
                                placeholder="Ketik ulang password baru">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200/50 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-medium text-sm hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
