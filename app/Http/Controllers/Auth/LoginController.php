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

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

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
