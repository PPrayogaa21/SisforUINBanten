<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biodata;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $biodata = $user->biodata;
        
        return view('profile.edit', compact('user', 'biodata'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update Biodata
        $biodataData = $request->only([
            'nama_lengkap', 'jabatan', 'bagian', 'pangkat_golongan', 'no_hp', 'alamat'
        ]);

        if ($request->hasFile('foto')) {
            $biodataData['foto'] = $request->file('foto')->store('biodata/foto', 'public');
        }

        Biodata::updateOrCreate(
            ['user_id' => $user->id],
            $biodataData
        );

        // Update User Model (nama & password)
        // No longer needed as nama is now in biodata
        // $user->update(['nama' => $request->nama_lengkap]);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.current_password' => 'Password saat ini tidak cocok.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.'
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }
}
