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

        // Lock only if user is currently acting as Narasumber AND has already filled their biodata once.
        $isLocked = session('active_role') === 'narasumber' && $user->biodata_verified;
        
        if ($isLocked) {
            return back()->with('error', 'Saat aktif sebagai Narasumber, data biodata terkunci untuk keperluan administrasi. Silakan hubungi admin jika memerlukan perubahan.');
        }

        $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'email'            => 'nullable|email|max:255|unique:biodata,email,' . $user->id . ',user_id',
            'tempat_lahir'     => 'nullable|string|max:255',
            'tanggal_lahir'    => 'nullable|date',
            'jabatan'          => 'nullable|string|max:255',
            'bagian'           => 'nullable|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'no_hp'            => 'nullable|string|max:20',
            'alamat_rumah'     => 'nullable|string',
            'alamat_kantor'    => 'nullable|string',
            'pendidikan_s1'    => 'nullable|string|max:255',
            'pendidikan_s2'    => 'nullable|string|max:255',
            'pendidikan_s3'    => 'nullable|string|max:255',
            'no_rekening'      => 'nullable|string|max:100',
            'npwp'             => 'nullable|string|max:50',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update Biodata
        $biodataData = $request->only([
            'nama_lengkap', 'email', 'tempat_lahir', 'tanggal_lahir',
            'jabatan', 'bagian', 'pangkat_golongan', 'no_hp',
            'alamat_rumah', 'alamat_kantor',
            'pendidikan_s1', 'pendidikan_s2', 'pendidikan_s3',
            'no_rekening', 'npwp',
        ]);

        if ($request->hasFile('foto')) {
            $biodataData['foto'] = $request->file('foto')->store('biodata/foto', 'public');
        }

        Biodata::updateOrCreate(
            ['user_id' => $user->id],
            $biodataData
        );

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
