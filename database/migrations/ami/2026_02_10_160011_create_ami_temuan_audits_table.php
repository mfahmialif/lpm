<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_temuan_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->enum('jenis_temuan', ['kesesuaian', 'observasi', 'ketidaksesuaian_minor', 'ketidaksesuaian_mayor']);
            $table->text('deskripsi');
            $table->text('rekomendasi')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_temuan_audits');
    }
};
