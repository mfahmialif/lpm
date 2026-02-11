<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_hasil_temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->string('judul');
            $table->longText('ringkasan');
            $table->enum('kategori', ['kesesuaian', 'observasi', 'ketidaksesuaian_minor', 'ketidaksesuaian_mayor']);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_hasil_temuans');
    }
};
