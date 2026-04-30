@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/50 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Informasi User Baru</h2>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors @error('nama') border-red-500 @enderror"
                            placeholder="John Doe">
                        @error('nama') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Username / ID <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors @error('username') border-red-500 @enderror"
                            placeholder="Username atau ID">
                        @error('username') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">NIP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors @error('nip') border-red-500 @enderror"
                            placeholder="199001012020121001">
                        @error('nip') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Jabatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors"
                            placeholder="Lektor / Guru Besar">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Bagian <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="bagian" value="{{ old('bagian') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors"
                            placeholder="Fakultas / Unit">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="user@example.com">
                        @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Role Akses <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors">
                            <option value="">-- Pilih Role --</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Bisa jadi Peserta/Narasumber)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors"
                            placeholder="Ketik ulang password">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200/50 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white font-medium text-sm hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
