<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            if (!Schema::hasColumn('biodata', 'alamat_rumah')) {
                $table->text('alamat_rumah')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'alamat_kantor')) {
                $table->text('alamat_kantor')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'pendidikan_s1')) {
                $table->string('pendidikan_s1')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'pendidikan_s2')) {
                $table->string('pendidikan_s2')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'pendidikan_s3')) {
                $table->string('pendidikan_s3')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'no_rekening')) {
                $table->string('no_rekening')->nullable();
            }
            if (!Schema::hasColumn('biodata', 'npwp')) {
                $table->string('npwp')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_rumah',
                'alamat_kantor',
                'tempat_lahir',
                'tanggal_lahir',
                'pendidikan_s1',
                'pendidikan_s2',
                'pendidikan_s3',
                'no_rekening',
                'npwp'
            ]);
        });
    }
};
