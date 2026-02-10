<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_asesmens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->foreignId('ami_indikator_id')->constrained('ami_indikators')->cascadeOnDelete();
            $table->string('skor_pilihan'); // Mulai 1, bisa multiple (comma separated)
            $table->text('catatan_asesor')->nullable();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_final')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['ami_sk_auditor_id', 'ami_indikator_id'], 'sk_indikator_asesmen_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_asesmens');
    }
};
