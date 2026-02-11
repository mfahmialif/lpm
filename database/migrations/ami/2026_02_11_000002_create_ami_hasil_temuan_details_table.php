<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_hasil_temuan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_hasil_temuan_id')->constrained('ami_hasil_temuans')->cascadeOnDelete();
            $table->foreignId('ami_temuan_audit_id')->constrained('ami_temuan_audits')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ami_hasil_temuan_id', 'ami_temuan_audit_id'], 'hasil_temuan_detail_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_hasil_temuan_details');
    }
};
