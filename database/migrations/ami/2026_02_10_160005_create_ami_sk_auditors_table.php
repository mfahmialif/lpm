<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_sk_auditors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_periode_id')->constrained('ami_periodes')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('ami_unit_audits')->cascadeOnDelete();
            $table->string('nomor_sk');
            $table->date('tanggal_sk');
            $table->foreignId('auditor_ketua_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'aktif', 'terkunci', 'selesai'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_sk_auditors');
    }
};
