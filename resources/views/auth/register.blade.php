@extends('layouts.auth')
@section('title', 'Registrasi')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Buat Akun Baru</h2>
<p class="text-slate-400 text-sm mb-6">Daftar untuk mengakses sistem kegiatan</p>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Username / NIP</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-id-card"></i></span>
            <input type="text" name="username" value="{{ old('username') }}" required
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Username / NIP">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-user"></i></span>
            <input type="text" name="nama" value="{{ old('nama') }}" required
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nama lengkap Anda">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-envelope"></i></span>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="email@example.com">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" required
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Minimal 8 karakter">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Password</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-lock"></i></span>
            <input type="password" name="password_confirmation" required
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Ulangi password">
        </div>
    </div>

    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
        <i class="fas fa-user-plus mr-2"></i> Daftar
    </button>
</form>

<p class="text-center text-sm text-slate-400 mt-6">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">Masuk di sini</a>
</p>
@endsection
