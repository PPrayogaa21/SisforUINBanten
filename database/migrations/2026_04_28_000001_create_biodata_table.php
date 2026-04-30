<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biodata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip')->nullable(); // Since users table also has nip or doesn't have it anymore
            $table->string('nama_lengkap')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('bagian')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('from_api')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biodata');
    }
};
