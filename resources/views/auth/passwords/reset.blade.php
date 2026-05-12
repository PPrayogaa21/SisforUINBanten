@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')

{{-- Title --}}
<div style="margin-bottom:28px;">
    <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-.02em;margin:0 0 6px;">Reset Password</h1>
    <p style="font-size:14px;color:#64748b;margin:0;">Silakan masukkan password baru Anda</p>
</div>

<form method="POST" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    {{-- Email --}}
    <div>
        <label for="email" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Alamat Email
        </label>
        <div class="input-wrap">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" id="email"
                   value="{{ $email ?? old('email') }}" required autofocus
                   class="auth-input"
                   placeholder="Masukkan email Anda">
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label for="password-field" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Password Baru
        </label>
        <div class="input-wrap">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password-field" required
                   class="auth-input" style="padding-right:44px;"
                   placeholder="Minimal 8 karakter">
            <button type="button" class="input-toggle" onclick="togglePw('password-field','pw-icon')">
                <i class="fas fa-eye" id="pw-icon"></i>
            </button>
        </div>
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="password-confirm-field" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Konfirmasi Password
        </label>
        <div class="input-wrap">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password_confirmation" id="password-confirm-field" required
                   class="auth-input" style="padding-right:44px;"
                   placeholder="Ulangi password baru">
            <button type="button" class="input-toggle" onclick="togglePw('password-confirm-field','pw-confirm-icon')">
                <i class="fas fa-eye" id="pw-confirm-icon"></i>
            </button>
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit" class="auth-btn" style="margin-top:6px;">
        <i class="fas fa-save"></i>
        Simpan Password Baru
    </button>
</form>

<script>
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') { f.type = 'text'; i.classList.replace('fa-eye','fa-eye-slash'); }
    else { f.type = 'password'; i.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endsection
