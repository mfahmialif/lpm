<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_evaluasi_diri_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_evaluasi_diri_id')->constrained('ami_evaluasi_diris')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_evaluasi_diri_files');
    }
};
