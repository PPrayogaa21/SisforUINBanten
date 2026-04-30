<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
            'nip' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'biodata_verified' => false,
            'status' => 1,
            'ket' => strtoupper($request->role),
            'hak_akses' => $request->role == 'admin' ? 1 : 2,
            'adalah' => $request->role == 'admin' ? 'ADMINISTRATOR' : 'SIMPEG | SISTEM PEGAWAI',
            'tglreg' => now()->format('Y-m-d'),
        ]);

        \App\Models\Biodata::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama,
            'jabatan' => $request->jabatan,
            'bagian' => $request->bagian,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user'],
            'nip' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'username' => $request->username,
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        \App\Models\Biodata::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama,
                'jabatan' => $request->jabatan,
                'bagian' => $request->bagian,
            ]
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
}
