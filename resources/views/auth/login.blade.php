@extends('layouts.auth')
@section('title', 'Login')

@section('content')

{{-- Title --}}
<div style="margin-bottom:28px;">
    <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-.02em;margin:0 0 6px;">Masuk</h1>
    <p style="font-size:14px;color:#64748b;margin:0;">Masukkan kredensial akun SITSFOR Anda</p>
</div>

<form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    {{-- Username --}}
    <div>
        <label for="login-username" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Username / NIP
        </label>
        <div class="input-wrap">
            <i class="fas fa-id-card input-icon"></i>
            <input type="text" name="username" id="login-username"
                   value="{{ old('username') }}" required autofocus
                   class="auth-input"
                   placeholder="Masukkan username atau NIP">
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label for="password-field" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Password
        </label>
        <div class="input-wrap">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password-field" required
                   class="auth-input" style="padding-right:44px;"
                   placeholder="Masukkan password">
            <button type="button" class="input-toggle" onclick="togglePw('password-field','pw-icon')">
                <i class="fas fa-eye" id="pw-icon"></i>
            </button>
        </div>
    </div>

    {{-- Remember & Forgot --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <input type="checkbox" name="remember" id="remember-me"
                   style="width:16px;height:16px;border-radius:5px;border:1.5px solid #cbd5e1;cursor:pointer;accent-color:#10b981;">
            <label for="remember-me" style="font-size:13px;color:#64748b;cursor:pointer;user-select:none;">
                Ingat saya
            </label>
        </div>
        <a href="{{ route('password.request') }}" style="font-size:13px;font-weight:600;color:#059669;text-decoration:none;transition:color .15s;" onmouseover="this.style.color='#047857'" onmouseout="this.style.color='#059669'">
            Lupa Password?
        </a>
    </div>

    {{-- Submit --}}
    <button type="submit" class="auth-btn" style="margin-top:6px;">
        <i class="fas fa-right-to-bracket"></i>
        Masuk ke Dashboard
    </button>
</form>

{{-- Divider --}}
<div class="auth-divider"><span>atau</span></div>

{{-- Register link --}}
<div style="text-align:center;">
    <p style="font-size:14px;color:#64748b;margin:0;">
        Belum punya akun?
        <a href="{{ route('register') }}"
           style="color:#059669;font-weight:700;text-decoration:none;margin-left:4px;transition:color .15s;"
           onmouseover="this.style.color='#047857'" onmouseout="this.style.color='#059669'">
            Daftar di sini &rarr;
        </a>
    </p>
</div>

{{-- Security note --}}
<div class="security-note">
    <i class="fas fa-shield-halved" style="color:#10b981;margin-top:1px;flex-shrink:0;"></i>
    <span>Koneksi Anda dilindungi. Data login Anda tidak akan pernah dibagikan kepada pihak ketiga.</span>
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
