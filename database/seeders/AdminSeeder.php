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
            'username' => 'ADMIN001',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'biodata_verified' => true,
            'status' => 1,
            'hak_akses' => 1,
        ]);

        Biodata::create([
            'user_id' => $admin->id,
            'nip' => '000000000000000001',
            'nama_lengkap' => 'Administrator Sistem',
            'email' => 'admin@sitsfor.ac.id',
            'email_verified_at' => now(),
            'jabatan' => 'Admin SITSFOR',
            'bagian' => 'PTIPD',
            'from_api' => false,
            'ket' => 'ADMIN',
            'adalah' => 'ADMINISTRATOR',
            'tgl_bergabung' => now()->format('Y-m-d'),
        ]);
    }
}
