<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MockBiodataController extends Controller
{
    /**
     * Mock endpoint for biodata API (development only)
     */
    public function show(string $nip): JsonResponse
    {
        // Simulated biodata database
        $mockData = [
            '596' => [
                'nip' => '197205042005011004',
                'nama_lengkap' => 'Dr. Achmad Maftuh Sujana, M.Ag',
                'jabatan' => 'Lektor pada Sekretaris pada Jurusan/Program Studi Aqidah dan Filsafat Islam',
                'bagian' => 'Jurusan/Program Studi Aqidah dan Filsafat Islam pada Fakultas Ushuluddin',
                'pangkat_golongan' => 'III/c - Penata',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Pendidikan No. 10, Kota Bandung',
            ],
            '412' => [
                'nip' => '197101172003121002',
                'nama_lengkap' => 'Dr. Apud S.Ag., M.Pd.',
                'jabatan' => 'Lektor Kepala pada Ketua pada Jurusan/Program Studi S3 dan S2 Manajemen',
                'bagian' => 'Jurusan/Program Studi S3 dan S2 Manajemen Pendidikan Islam pada Pascasarjana',
                'pangkat_golongan' => 'III/b - Penata Muda Tk. I',
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Ilmu No. 25, Kota Bandung',
            ],
            '577' => [
                'nip' => '199012172018012002',
                'nama_lengkap' => 'Roza Puspita, M.Sc',
                'jabatan' => 'Lektor pada Ketua pada Jurusan / Program Studi Kimia pada Fakultas Sains',
                'bagian' => 'Jurusan / Program Studi Kimia pada Fakultas Sains',
                'pangkat_golongan' => 'IV/d - Pembina Utama Madya',
                'no_hp' => '083456789012',
                'alamat' => 'Jl. Kampus No. 5, Kota Bandung',
            ],
        ];

        if (isset($mockData[$nip])) {
            return response()->json([
                'success' => true,
                'message' => 'Biodata ditemukan',
                'data' => $mockData[$nip],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Biodata tidak ditemukan',
            'data' => null,
        ], 404);
    }
}
