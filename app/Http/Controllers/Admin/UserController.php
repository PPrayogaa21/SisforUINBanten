<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('biodata', function($bq) use ($search) {
                    $bq->where('nama_lengkap', 'ilike', "%{$search}%")
                       ->orWhere('email', 'ilike', "%{$search}%");
                })->orWhere('username', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('account_status')) {
            $query->where('account_status', $request->account_status);
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }
    public function approvalIndex()
    {
        $users = User::where('account_status', 'pending')
            ->latest()
            ->paginate(10);
            
        return view('admin.users.approval', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => [
                'nullable', 'string', 'max:255',
                Rule::unique('users', 'username')->where(function ($query) {
                    return $query->whereIn('account_status', ['approved', 'pending']);
                })
            ],
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('biodata', 'email')->where(function ($query) {
                    return $query->whereExists(function ($q) {
                        $q->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.id', 'biodata.user_id')
                            ->whereIn('account_status', ['approved', 'pending']);
                    });
                })
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
        ]);

        // Cek jika ada user yang di-reject dengan identifier yang sama
        $existingUser = User::where(function($q) use ($request) {
            $q->where('username', $request->username)
              ->orWhereHas('biodata', fn($bq) => $bq->where('email', $request->email));
        })->where('account_status', 'rejected')->first();

        if ($existingUser) {
            $existingUser->update([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'account_status' => 'approved', // Admin creates, so auto approve or keep pending? 
                // Usually admin created accounts are active.
                'status' => 1,
                'hak_akses' => $request->role == 'admin' ? 1 : 2,
            ]);

            $existingUser->biodata()->update([
                'nama_lengkap' => $request->nama,
                'email' => $request->email,
                'ket' => strtoupper($request->role),
                'adalah' => $request->role == 'admin' ? 'ADMINISTRATOR' : 'SIMPEG | SISTEM PEGAWAI',
            ]);

            return redirect()->route('admin.users.edit', $existingUser)->with('success', 'Akun (sebelumnya ditolak) berhasil diaktifkan kembali.');
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'biodata_verified' => false,
            'status' => 1,
            'hak_akses' => $request->role == 'admin' ? 1 : 2,
        ]);

        \App\Models\Biodata::create([
            'user_id' => $user->id,
            'nama_lengkap' => $request->nama,
            'email' => $request->email,
            'ket' => strtoupper($request->role),
            'adalah' => $request->role == 'admin' ? 'ADMINISTRATOR' : 'SIMPEG | SISTEM PEGAWAI',
            'tgl_bergabung' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Akun berhasil dibuat. Silakan lengkapi biodata.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('biodata', 'email')->ignore($user->biodata->id ?? null)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $data = [
            'username' => $request->username,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        \App\Models\Biodata::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'nama_lengkap', 'email', 'nip', 'jabatan', 'bagian', 'pangkat_golongan', 
                'tempat_lahir', 'tanggal_lahir', 'pendidikan_s1', 'pendidikan_s2', 'pendidikan_s3', 
                'no_rekening', 'npwp', 'alamat_kantor', 'alamat_rumah', 'no_hp'
            ])
        );

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function approve(User $user)
    {
        $user->update(['account_status' => 'approved']);
        return back()->with('success', 'Akun berhasil disetujui.');
    }

    public function reject(User $user)
    {
        $user->update(['account_status' => 'rejected']);
        return back()->with('success', 'Akun telah ditolak.');
    }
}
