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
        Schema::create('prodi_competencies', function (Blueprint $table) {
            $table->id();

            // Menggunakan unsignedBigInteger untuk prodi_id agar aman jika tabel prodi belum ada / belum terdeteksi
            $table->unsignedBigInteger('prodi_id');
            // $table->foreignId('prodi_id')->constrained('prodi')->cascadeOnDelete();

            $table->foreignId('competency_id')
                ->constrained('competencies')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['prodi_id', 'competency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi_competencies');
    }
};
