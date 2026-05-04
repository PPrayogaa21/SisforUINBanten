<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom ke biodata jika belum ada
        Schema::table('biodata', function (Blueprint $table) {
            if (!Schema::hasColumn('biodata', 'email')) {
                $table->string('email')->unique()->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('biodata', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('biodata', 'adalah')) {
                $table->string('adalah')->nullable()->after('from_api');
            }
            if (!Schema::hasColumn('biodata', 'ket')) {
                $table->text('ket')->nullable()->after('adalah');
            }
            if (!Schema::hasColumn('biodata', 'tgl_bergabung')) {
                $table->date('tgl_bergabung')->nullable()->after('ket');
            }

            // Pastikan user_id unique
            // Kita coba buat unique, tapi jika sudah ada index mungkin gagal. 
            // Namun secara default foreignId() tidak membuat unique.
            $table->unsignedBigInteger('user_id')->unique()->change();
        });

        // 2. Pastikan semua user memiliki record di biodata sebelum migrasi data
        $usersWithoutBiodata = DB::table('users')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('biodata')
                    ->whereRaw('biodata.user_id = users.id');
            })->get();

        foreach ($usersWithoutBiodata as $user) {
            DB::table('biodata')->insert([
                'user_id' => $user->id,
                'nama_lengkap' => $user->nama ?? $user->username,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Migrasi Data dari users ke biodata
        DB::statement('
            UPDATE biodata
            SET nama_lengkap = COALESCE(users.nama, biodata.nama_lengkap),
                email = users.email,
                email_verified_at = users.email_verified_at,
                adalah = users.adalah,
                ket = users.ket,
                tgl_bergabung = users.tglreg
            FROM users
            WHERE biodata.user_id = users.id
        ');

        // 4. Hapus kolom dari users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'email', 'email_verified_at', 'adalah', 'tglreg', 'ket']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom ke users
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('adalah')->nullable();
            $table->date('tglreg')->nullable();
            $table->text('ket')->nullable();
        });

        // 2. Kembalikan data dari biodata ke users
        DB::statement('
            UPDATE users
            SET nama = biodata.nama_lengkap,
                email = biodata.email,
                email_verified_at = biodata.email_verified_at,
                adalah = biodata.adalah,
                tglreg = biodata.tgl_bergabung,
                ket = biodata.ket
            FROM biodata
            WHERE users.id = biodata.user_id
        ');

        // Hapus kolom dari biodata
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropColumn(['email', 'email_verified_at', 'adalah', 'ket', 'tgl_bergabung']);
            
            // Drop unique constraint (ini agak tricky di Laravel tergantung DB driver)
            $table->dropUnique(['user_id']);
        });
    }
};
