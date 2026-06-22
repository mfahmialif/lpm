<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_rip', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_surat')->nullable();
            $table->string('perihal')->nullable();
            $table->string('yang_mengeluarkan')->nullable();
            $table->string('path')->nullable();
            $table->enum('status', ['acc', 'tolak'])->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units_dokument')->onDelete('set null');
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_rip');
    }
};
