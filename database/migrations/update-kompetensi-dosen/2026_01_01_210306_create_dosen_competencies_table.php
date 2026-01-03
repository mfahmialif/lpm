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
        Schema::create('dosen_competencies', function (Blueprint $table) {
            $table->id();

            // Menggunakan nama tabel 'mst_dosen' sesuai dengan migrasi yang terakhir dibuat
            $table->foreignId('dosen_id')
                ->constrained('mst_dosen')
                ->cascadeOnDelete();

            $table->foreignId('prodi_competency_id')
                ->constrained('prodi_competencies')
                ->cascadeOnDelete();

            $table->foreignId('periode_akademik_id')
                ->constrained('periode_akademik')
                ->cascadeOnDelete();

            $table->foreignId('sk_kompetensi_id')
                ->constrained('sk_kompetensi')
                ->cascadeOnDelete();

            $table->enum('status', [
                'MENUNGGU',
                'AKTIF',
                'DITOLAK',
                'KADALUARSA'
            ])->nullable();

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->timestamps();

            // 🔥 KUNCI UTAMA: anti bentrok kompetensi
            // Memberikan nama index custom agar tidak terlalu panjang
            $table->unique(['prodi_competency_id', 'periode_akademik_id'], 'dosen_comp_prodi_periode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_competencies');
    }
};
