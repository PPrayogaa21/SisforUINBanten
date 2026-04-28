<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuesioner_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('kuesioner_response')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('kuesioner_pertanyaan')->onDelete('cascade');
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioner_jawaban');
    }
};
