<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Biodata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'username' => 'ADMIN001', // Using username instead of nip
            'nama' => 'Administrator',
            'email' => 'admin@sitsfor.ac.id',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'biodata_verified' => true,
            'email_verified_at' => now(),
            'status' => 1,
            'ket' => 'ADMIN',
            'hak_akses' => 1,
            'adalah' => 'ADMINISTRATOR',
            'tglreg' => now()->format('Y-m-d'),
        ]);

        Biodata::create([
            'user_id' => $admin->id,
            'nip' => '000000000000000001',
            'nama_lengkap' => 'Administrator Sistem',
            'jabatan' => 'Admin SITSFOR',
            'bagian' => 'PTIPD',
            'from_api' => false,
        ]);
    }
}
