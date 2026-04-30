<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|min:1',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Username/NIP sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'biodata_verified' => false,
            'status' => 1,
            'ket' => 'USER',
            'hak_akses' => 2,
            'adalah' => 'SIMPEG | SISTEM PEGAWAI',
            'tglreg' => now()->format('Y-m-d'),
        ]);

        Auth::login($user);

        return redirect()->route('biodata.create')
            ->with('info', 'Registrasi berhasil! Silakan lengkapi biodata Anda.');
    }
}
