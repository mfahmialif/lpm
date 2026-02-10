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
        Schema::create('akreditasi_kampus', function (Blueprint $table) {
            $table->id();
            $table->string('perguruan_tinggi')->nullable();
            $table->string('akreditasi')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('peringkat')->nullable();
            $table->date('kadaluarsa')->nullable();
            $table->enum('status', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akreditasi_kampus');
    }
};
