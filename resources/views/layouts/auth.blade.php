<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - SITSFOR Kegiatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        html, body { height: 100%; margin: 0; padding: 0; }

        .auth-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Left branding panel ── */
        .auth-left {
            width: 46%;
            flex-shrink: 0;
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: linear-gradient(150deg, #064e3b 0%, #065f46 25%, #059669 55%, #0d9488 100%);
        }

        .auth-left .dots {
            position: absolute;
            inset: 0;
            opacity: .07;
            background-image: radial-gradient(circle, #fff 1.2px, transparent 1.2px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .auth-left .ring1 {
            position: absolute;
            top: -80px; right: -80px;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .auth-left .ring2 {
            position: absolute;
            bottom: -100px; left: -60px;
            width: 340px; height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .auth-left .ring3 {
            position: absolute;
            top: 40%; right: -50px;
            width: 200px; height: 200px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.12);
            pointer-events: none;
        }

        .auth-left-inner {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 48px 52px;
        }

        /* ── Right form panel ── */
        .auth-right {
            flex: 1;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .auth-form-box {
            width: 100%;
            max-width: 400px;
            animation: slideUp .45s ease both;
        }

        /* ── Feature pills ── */
        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.85);
            font-size: 11px;
            font-weight: 600;
            margin-right: 6px;
            margin-bottom: 8px;
        }

        /* ── Inputs ── */
        .auth-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #0f172a;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color .15s, box-shadow .15s, background .15s;
            outline: none;
        }

        .auth-input::placeholder { color: #94a3b8; }

        .auth-input:hover {
            border-color: #cbd5e1;
            background: #fff;
        }

        .auth-input:focus {
            border-color: #10b981;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(16, 185, 129, .14);
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
            transition: color .15s;
        }

        .input-wrap:focus-within .input-icon { color: #10b981; }

        .input-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 13px;
            padding: 4px;
            transition: color .15s;
        }

        .input-toggle:hover { color: #475569; }

        /* ── Submit button ── */
        .auth-btn {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            box-shadow: 0 4px 20px rgba(5, 150, 105, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform .18s, box-shadow .18s, filter .18s;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(5, 150, 105, .45);
            filter: brightness(1.05);
        }

        .auth-btn:active { transform: scale(.98); }

        /* ── Alerts ── */
        .alert-info  { background:#eff6ff; border:1.5px solid #bfdbfe; color:#1d4ed8; border-radius:14px; padding:14px 16px; font-size:13px; margin-bottom:20px; display:flex; gap:10px; align-items:flex-start; }
        .alert-ok    { background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d; border-radius:14px; padding:14px 16px; font-size:13px; margin-bottom:20px; display:flex; gap:10px; align-items:flex-start; }
        .alert-error { background:#fef2f2; border:1.5px solid #fecaca; color:#b91c1c; border-radius:14px; padding:14px 16px; font-size:13px; margin-bottom:20px; }

        /* ── Divider ── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0;
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }
        .auth-divider span { font-size: 11px; color: #94a3b8; font-weight: 500; }

        /* ── Security note ── */
        .security-note {
            margin-top: 24px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* ── Animations ── */
        @keyframes slideUp {
            from { opacity:0; transform:translateY(22px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .auth-left { display: none; }
            .auth-right { padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-shell">

    {{-- ===== LEFT BRANDING PANEL ===== --}}
    <div class="auth-left">
        <div class="dots"></div>
        <div class="ring1"></div>
        <div class="ring2"></div>
        <div class="ring3"></div>

        <div class="auth-left-inner">

            {{-- Logo --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:auto;">
                <div style="width:44px;height:44px;border-radius:14px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;padding:8px;flex-shrink:0;">
                    <img src="/img/logo-uin.png" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
                </div>
                <div>
                    <div style="font-size:15px;font-weight:900;color:#fff;line-height:1;">SITSFOR</div>
                    <div style="font-size:10px;color:rgba(167,243,208,.7);font-weight:500;letter-spacing:.08em;margin-top:3px;">UIN SMH Banten</div>
                </div>
            </div>

            {{-- Hero --}}
            <div style="margin:auto 0;">
                <div style="width:64px;height:64px;border-radius:20px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-bottom:32px;box-shadow:0 8px 32px rgba(0,0,0,.2);">
                    <i class="fas fa-building-columns" style="color:#fff;font-size:24px;"></i>
                </div>

                <h2 style="font-size:36px;font-weight:900;color:#fff;line-height:1.18;letter-spacing:-.02em;margin:0 0 16px;">
                    Sistem Informasi<br>
                    <span style="color:#6ee7b7;">Kegiatan</span> Luar<br>Kantor
                </h2>

                <p style="color:rgba(209,250,229,.7);font-size:14px;font-weight:500;line-height:1.7;max-width:320px;margin:0 0 32px;">
                    Platform terpadu untuk pengelolaan, pemantauan, dan dokumentasi seluruh kegiatan luar kantor UIN Sultan Maulana Hasanuddin Banten.
                </p>

                <div>
                    <span class="feature-pill"><i class="fas fa-calendar-check" style="color:#6ee7b7;font-size:10px;"></i> Manajemen Kegiatan</span>
                    <span class="feature-pill"><i class="fas fa-users" style="color:#6ee7b7;font-size:10px;"></i> Multi Peran</span>
                    <span class="feature-pill"><i class="fas fa-file-lines" style="color:#6ee7b7;font-size:10px;"></i> Laporan Digital</span>
                </div>
            </div>

            {{-- Footer --}}
            <div style="margin-top:auto;">
                <p style="font-size:11px;color:rgba(167,243,208,.35);font-weight:500;">
                    &copy; {{ date('Y') }} UIN Sultan Maulana Hasanuddin Banten
                </p>
            </div>

        </div>
    </div>

    {{-- ===== RIGHT FORM PANEL ===== --}}
    <div class="auth-right">
        <div class="auth-form-box">

            {{-- Mobile logo --}}
            <div style="display:none;" class="mobile-logo">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:32px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:#f0fdf4;border:1px solid #d1fae5;display:flex;align-items:center;justify-content:center;padding:6px;">
                        <img src="/img/logo-uin.png" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:900;color:#0f172a;">SITSFOR</div>
                        <div style="font-size:10px;color:#64748b;">Kegiatan Luar Kantor</div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('info'))
                <div class="alert-info">
                    <i class="fas fa-info-circle" style="color:#3b82f6;margin-top:1px;flex-shrink:0;"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-ok">
                    <i class="fas fa-check-circle" style="color:#22c55e;margin-top:1px;flex-shrink:0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <div style="display:flex;align-items:center;gap:8px;font-weight:700;margin-bottom:8px;">
                        <i class="fas fa-triangle-exclamation" style="color:#f87171;"></i> Terdapat kesalahan:
                    </div>
                    <ul style="margin:0;padding-left:4px;list-style:none;">
                        @foreach($errors->all() as $error)
                            <li style="display:flex;gap:8px;align-items:flex-start;margin-bottom:4px;">
                                <span style="color:#f87171;flex-shrink:0;">•</span>{{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </div>

</div>

<style>
@media (max-width: 900px) {
    .mobile-logo { display: block !important; }
}
</style>

</body>
</html>
