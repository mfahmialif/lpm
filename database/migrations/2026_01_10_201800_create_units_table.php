<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units_dokument', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->enum('jenis', ['Prodi', 'Fakultas', 'Institusi'])->nullable();
            $table->string('fakultas')->nullable();
            $table->enum('jenjang', ['S1', 'S2', 'S3'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units_dokument');
    }
};
