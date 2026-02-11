<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_target_amis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->string('kode_target')->unique();
            $table->year('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('standar_acuan')->nullable();
            $table->text('ruang_lingkup')->nullable();
            $table->enum('status', ['draft', 'aktif', 'ditutup'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_target_amis');
    }
};
