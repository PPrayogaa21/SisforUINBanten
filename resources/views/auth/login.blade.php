@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Selamat Datang</h2>
<p class="text-slate-400 text-sm mb-6">Masuk ke akun SITSFOR Anda</p>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Username / NIP</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-id-card"></i></span>
            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/50 transition-all"
                placeholder="Masukkan Username atau NIP Anda">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" required id="password-field"
                class="w-full pl-10 pr-12 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/50 transition-all"
                placeholder="Masukkan password">
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300">
                <i class="fas fa-eye" id="password-toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-emerald-500 focus:ring-emerald-400/50">
            <span class="text-sm text-slate-400">Ingat saya</span>
        </label>
    </div>

    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:from-emerald-400 hover:to-teal-400 transform hover:-translate-y-0.5 transition-all duration-200">
        <i class="fas fa-right-to-bracket mr-2"></i> Masuk
    </button>
</form>

<p class="text-center text-sm text-slate-400 mt-6">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">Daftar di sini</a>
</p>

<script>
function togglePassword() {
    const field = document.getElementById('password-field');
    const icon = document.getElementById('password-toggle-icon');
    if (field.type === 'password') { field.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
    else { field.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
}
</script>
@endsection
