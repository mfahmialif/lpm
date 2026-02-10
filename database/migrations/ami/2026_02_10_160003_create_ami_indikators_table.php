<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_indikators', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->text('pertanyaan');
            $table->enum('jenis_unit', ['Prodi', 'Fakultas', 'Institusi', 'Semua'])->default('Semua');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_indikators');
    }
};
