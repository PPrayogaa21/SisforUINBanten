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
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['nip' => $credentials['nip'], 'password' => $credentials['password']], $request->boolean('remember'))) {
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
            return redirect()->route('select-role');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->onlyInput('nip');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
