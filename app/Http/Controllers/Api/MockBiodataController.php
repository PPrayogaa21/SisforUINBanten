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
            '198501012010011001' => [
                'nip' => '198501012010011001',
                'nama_lengkap' => 'Dr. Ahmad Fauzi, M.Kom.',
                'jabatan' => 'Dosen',
                'unit_kerja' => 'Fakultas Sains dan Teknologi',
                'pangkat_golongan' => 'III/c - Penata',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Pendidikan No. 10, Kota Bandung',
            ],
            '199001012015012001' => [
                'nip' => '199001012015012001',
                'nama_lengkap' => 'Siti Nurhaliza, S.Pd., M.Pd.',
                'jabatan' => 'Dosen',
                'unit_kerja' => 'Fakultas Tarbiyah dan Keguruan',
                'pangkat_golongan' => 'III/b - Penata Muda Tk. I',
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Ilmu No. 25, Kota Bandung',
            ],
            '197505152003121003' => [
                'nip' => '197505152003121003',
                'nama_lengkap' => 'Prof. Dr. H. Budi Santoso, M.Si.',
                'jabatan' => 'Guru Besar',
                'unit_kerja' => 'Fakultas Ushuluddin',
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
