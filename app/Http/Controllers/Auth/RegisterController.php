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
            'email' => 'required|email|unique:biodata,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username/NIP sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'biodata_verified' => false,
            'status' => 1,
            'hak_akses' => 2,
        ]);

        \App\Models\Biodata::create([
            'user_id' => $user->id,
            'nip' => $validated['username'],
            'nama_lengkap' => $validated['nama'],
            'email' => $validated['email'],
            'ket' => 'USER',
            'adalah' => 'SIMPEG | SISTEM PEGAWAI',
            'tgl_bergabung' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Admin.');
    }
}
