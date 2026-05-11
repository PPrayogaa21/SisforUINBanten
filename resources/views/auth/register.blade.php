@extends('layouts.auth')
@section('title', 'Registrasi')

@section('content')

{{-- Title --}}
<div style="margin-bottom:28px;">
    <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-.02em;margin:0 0 6px;">Buat Akun</h1>
    <p style="font-size:14px;color:#64748b;margin:0;">Daftarkan diri Anda untuk mengakses sistem kegiatan</p>
</div>

<form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:16px;">
    @csrf

    {{-- Username --}}
    <div>
        <label for="reg-username" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Username / NIP
        </label>
        <div class="input-wrap">
            <i class="fas fa-id-card input-icon"></i>
            <input type="text" name="username" id="reg-username"
                   value="{{ old('username') }}" required
                   class="auth-input"
                   placeholder="Username atau NIP Anda">
        </div>
    </div>

    {{-- Nama Lengkap --}}
    <div>
        <label for="reg-nama" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Nama Lengkap
        </label>
        <div class="input-wrap">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="nama" id="reg-nama"
                   value="{{ old('nama') }}" required
                   class="auth-input"
                   placeholder="Nama lengkap Anda">
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="reg-email" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Email
        </label>
        <div class="input-wrap">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" id="reg-email"
                   value="{{ old('email') }}" required
                   class="auth-input"
                   placeholder="email@example.com">
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label for="reg-password" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Password
        </label>
        <div class="input-wrap">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="reg-password" required
                   class="auth-input" style="padding-right:44px;"
                   placeholder="Minimal 8 karakter">
            <button type="button" class="input-toggle" onclick="togglePw('reg-password','pw1-icon')">
                <i class="fas fa-eye" id="pw1-icon"></i>
            </button>
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label for="reg-password-confirm" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Konfirmasi Password
        </label>
        <div class="input-wrap">
            <i class="fas fa-shield-check input-icon"></i>
            <input type="password" name="password_confirmation" id="reg-password-confirm" required
                   class="auth-input" style="padding-right:44px;"
                   placeholder="Ulangi password">
            <button type="button" class="input-toggle" onclick="togglePw('reg-password-confirm','pw2-icon')">
                <i class="fas fa-eye" id="pw2-icon"></i>
            </button>
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit" class="auth-btn" style="margin-top:6px;">
        <i class="fas fa-user-plus"></i>
        Buat Akun Sekarang
    </button>
</form>

{{-- Divider --}}
<div class="auth-divider"><span>atau</span></div>

{{-- Login link --}}
<div style="text-align:center;">
    <p style="font-size:14px;color:#64748b;margin:0;">
        Sudah punya akun?
        <a href="{{ route('login') }}"
           style="color:#059669;font-weight:700;text-decoration:none;margin-left:4px;transition:color .15s;"
           onmouseover="this.style.color='#047857'" onmouseout="this.style.color='#059669'">
            Masuk di sini &rarr;
        </a>
    </p>
</div>

<script>
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') { f.type = 'text'; i.classList.replace('fa-eye','fa-eye-slash'); }
    else { f.type = 'password'; i.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endsection
