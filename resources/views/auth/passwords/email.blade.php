@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('content')

{{-- Title --}}
<div style="margin-bottom:28px;">
    <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-.02em;margin:0 0 6px;">Lupa Password</h1>
    <p style="font-size:14px;color:#64748b;margin:0;">Masukkan email Anda untuk menerima link reset password</p>
</div>

@if (session('status'))
    <div style="background-color:#ecfdf5;color:#059669;padding:12px;border-radius:8px;font-size:13px;margin-bottom:20px;border:1px solid #a7f3d0;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('status') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    {{-- Email --}}
    <div>
        <label for="email" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">
            Alamat Email
        </label>
        <div class="input-wrap">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}" required autofocus
                   class="auth-input"
                   placeholder="Masukkan email Anda">
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit" class="auth-btn" style="margin-top:6px;">
        <i class="fas fa-paper-plane"></i>
        Kirim Link Reset
    </button>
</form>

{{-- Divider --}}
<div class="auth-divider"><span>atau</span></div>

{{-- Login link --}}
<div style="text-align:center;">
    <p style="font-size:14px;color:#64748b;margin:0;">
        Ingat password Anda?
        <a href="{{ route('login') }}"
           style="color:#059669;font-weight:700;text-decoration:none;margin-left:4px;transition:color .15s;"
           onmouseover="this.style.color='#047857'" onmouseout="this.style.color='#059669'">
            Masuk di sini &rarr;
        </a>
    </p>
</div>

@endsection
