<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_evaluasi_diris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->foreignId('ami_indikator_id')->constrained('ami_indikators')->cascadeOnDelete();
            $table->longText('jawaban')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['ami_sk_auditor_id', 'ami_indikator_id'], 'sk_indikator_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_evaluasi_diris');
    }
};
