<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => [
                'required', 'string', 'min:1',
                Rule::unique('users', 'username')->where(function ($query) {
                    return $query->whereIn('account_status', ['approved', 'pending']);
                })
            ],
            'nama' => 'required|string|max:255',
            'email' => [
                'required', 'email',
                Rule::unique('biodata', 'email')->where(function ($query) {
                    return $query->whereExists(function ($q) {
                        $q->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.id', 'biodata.user_id')
                            ->whereIn('account_status', ['approved', 'pending']);
                    });
                })
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Username/NIP sudah terdaftar dan aktif.',
            'email.unique' => 'Email sudah terdaftar dan aktif.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Cek apakah ada akun yang sebelumnya di-reject dengan email atau username yang sama
        $existingUser = User::where('username', $request->username)
            ->orWhereHas('biodata', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->first();

        if ($existingUser && $existingUser->account_status === 'rejected') {
            // Update akun yang di-reject menjadi pending lagi
            $existingUser->update([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'account_status' => 'pending',
                'biodata_verified' => false,
                'status' => 1,
            ]);

            $existingUser->biodata()->update([
                'nama_lengkap' => $request->nama,
                'email' => $request->email,
                'nip' => $request->username,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi ulang berhasil! Akun Anda sedang menunggu persetujuan Admin.');
        }

        // Registrasi akun baru
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'biodata_verified' => false,
            'status' => 1,
            'hak_akses' => 2,
            'account_status' => 'pending',
        ]);

        \App\Models\Biodata::create([
            'user_id' => $user->id,
            'nip' => $request->username,
            'nama_lengkap' => $request->nama,
            'email' => $request->email,
            'ket' => 'USER',
            'adalah' => 'SIMPEG | SISTEM PEGAWAI',
            'tgl_bergabung' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Admin.');
    }
}
