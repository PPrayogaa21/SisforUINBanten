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
            'nip' => '000000000000000001',
            'name' => 'Administrator',
            'email' => 'admin@sitsfor.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'biodata_verified' => true,
            'email_verified_at' => now(),
        ]);

        Biodata::create([
            'user_id' => $admin->id,
            'nip' => $admin->nip,
            'nama_lengkap' => 'Administrator Sistem',
            'jabatan' => 'Admin SITSFOR',
            'unit_kerja' => 'PTIPD',
            'from_api' => false,
        ]);
    }
}
