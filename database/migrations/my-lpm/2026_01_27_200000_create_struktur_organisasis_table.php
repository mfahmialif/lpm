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
        Schema::create('struktur_organisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_lpm_id')->constrained('periode_lpms')->onDelete('cascade');
            $table->string('penasehat')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('ketua_lpm')->nullable();
            $table->text('anggota')->nullable();
            $table->string('kjm_pasca_sarjana')->nullable();
            $table->string('gjm_prodi_mpi_s2')->nullable();
            $table->string('gjm_prodi_pai_s2')->nullable();
            $table->string('gjm_prodi_pba_s2')->nullable();
            $table->string('gjm_prodi_pai_s3')->nullable();
            $table->string('gjm_prodi_pba_s3')->nullable();
            $table->string('kjm_fakultas_syariah')->nullable();
            $table->string('gjm_prodi_hki')->nullable();
            $table->string('gjm_prodi_esy')->nullable();
            $table->string('kjm_fakultas_tarbiyah')->nullable();
            $table->string('gjm_prodi_pai')->nullable();
            $table->string('gjm_prodi_pba')->nullable();
            $table->string('gjm_prodi_mpi')->nullable();
            $table->string('kjm_fakultas_dakwah')->nullable();
            $table->string('gjm_prodi_kpi')->nullable();
            $table->string('gjm_prodi_bki')->nullable();
            $table->string('gjm_prodi_mhu')->nullable();
            $table->string('kjm_fakultas_adab')->nullable();
            $table->string('gjm_prodi_spi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasis');
    }
};
