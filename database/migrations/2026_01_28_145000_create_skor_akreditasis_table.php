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
        Schema::create('skor_akreditasis', function (Blueprint $table) {
            $table->id();
            $table->string('perguruan_tinggi')->nullable();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');
            $table->string('strata')->nullable();
            $table->string('wilayah')->nullable();
            $table->string('no_sk')->nullable();
            $table->date('tgl_kadaluarsa')->nullable();
            $table->string('peringkat')->nullable();
            $table->string('tahun_sk')->nullable();
            $table->enum('status', ['masih berlaku', 'kadaluarsa'])->default('masih berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skor_akreditasis');
    }
};
