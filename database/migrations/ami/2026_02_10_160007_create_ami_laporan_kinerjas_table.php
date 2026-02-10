<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_laporan_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->longText('ringkasan');
            $table->longText('rencana_tindak_lanjut')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_laporan_kinerjas');
    }
};
