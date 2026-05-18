@extends('layouts.app')
@section('title', 'Ubah Password')
@section('page-title', 'Keamanan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
            <i class="fas fa-key text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Ubah Password</h2>
            <p class="text-sm text-slate-500">Pastikan akun Anda menggunakan password yang aman.</p>
        </div>
    </div>

    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Saat Ini <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required minlength="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
            </div>
            
            <div class="pt-2 text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <i class="fas fa-info-circle mr-1"></i> Password harus terdiri dari minimal 6 karakter.
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('profile.edit') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left text-slate-400"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-save"></i> Perbarui Password
            </button>
        </div>
    </form>
</div>
@endsection
