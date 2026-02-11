<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_rtm_temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_rtm_id')->constrained('ami_rtms')->cascadeOnDelete();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->foreignId('ami_temuan_audit_id')->constrained('ami_temuan_audits')->cascadeOnDelete();
            $table->longText('keputusan')->nullable();
            $table->longText('rencana_tindak_lanjut')->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('target_selesai')->nullable();
            $table->enum('status_tindak_lanjut', ['open', 'on_progress', 'selesai'])->default('open');
            $table->timestamps();

            $table->unique(['ami_rtm_id', 'ami_temuan_audit_id'], 'rtm_temuan_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_rtm_temuans');
    }
};
