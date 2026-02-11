<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_rtms', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rtm')->unique();
            $table->date('tanggal_rtm');
            $table->foreignId('pimpinan_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'sah', 'ditutup'])->default('draft');
            $table->longText('catatan_umum')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_rtms');
    }
};
