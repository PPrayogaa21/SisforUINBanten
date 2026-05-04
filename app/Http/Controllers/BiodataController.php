<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Services\BiodataApiService;
use Illuminate\Http\Request;

class BiodataController extends Controller
{
    protected BiodataApiService $biodataService;

    public function __construct(BiodataApiService $biodataService)
    {
        $this->biodataService = $biodataService;
    }

    public function create()
    {
        $user = auth()->user();
        $biodata = $user->biodata;

        // Coba fetch dari API jika belum ada
        if (!$biodata) {
            $biodata = $this->biodataService->fetchAndStore($user);
        }

        return view('auth.biodata', compact('biodata'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('biodata/foto', 'public');
        }

        Biodata::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, [
                'nip' => $user->username,
                'from_api' => false,
            ])
        );

        $user->update(['biodata_verified' => true]);

        if (session('just_registered')) {
            session()->forget('just_registered');
            auth()->logout();
            return redirect()->route('login')
                ->with('success', 'Pendaftaran dan pengisian biodata berhasil. Silakan login untuk melanjutkan.');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Biodata berhasil disimpan.');
        }

        return redirect()->route('dashboard');
    }
}
