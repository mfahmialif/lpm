<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_rubrik_skors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_indikator_id')->constrained('ami_indikators')->cascadeOnDelete();
            $table->tinyInteger('skor'); // 1-4
            $table->text('deskripsi');
            $table->timestamps();

            // $table->unique(['ami_indikator_id', 'skor']); // Removed for flexible scoring
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_rubrik_skors');
    }
};
