<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BiodataApiService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected BiodataApiService $biodataService;

    public function __construct(BiodataApiService $biodataService)
    {
        $this->biodataService = $biodataService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user) {
            $dbPassword = $user->getRawOriginal('password');
            // Cek apakah password di database tidak menggunakan format Bcrypt
            if ($dbPassword && !str_starts_with($dbPassword, '$2y$') && !str_starts_with($dbPassword, '$2a$') && !str_starts_with($dbPassword, '$2b$')) {
                // Jika password cocok dengan plain text atau MD5, update ke Bcrypt
                if ($dbPassword === $credentials['password'] || $dbPassword === md5($credentials['password'])) {
                    $user->password = $credentials['password']; // Otomatis di-hash oleh cast 'hashed'
                    $user->save();
                } else {
                    // Jika salah, gagalkan login agar Auth::attempt tidak error BcryptHasher
                    return back()->withErrors([
                        'username' => 'Username/NIP atau password salah.',
                    ])->onlyInput('username');
                }
            }
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->account_status === 'pending') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda sedang menunggu persetujuan Admin.']);
            }

            if ($user->account_status === 'rejected') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda ditolak oleh Admin.']);
            }

            // Admin langsung ke dashboard admin
            if ($user->isAdmin()) {
                session(['active_role' => 'admin']);
                return redirect()->route('admin.dashboard');
            }

            // Cek biodata via API eksternal
            if (!$user->biodata_verified) {
                $biodata = $this->biodataService->fetchAndStore($user);
                if (!$biodata) {
                    // Biodata tidak ditemukan di API, user harus isi manual
                    return redirect()->route('biodata.create')
                        ->with('info', 'Biodata tidak ditemukan di sistem. Silakan lengkapi biodata Anda.');
                }
            }

            // Redirect ke pemilihan role
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username/NIP atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
