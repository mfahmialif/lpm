<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_rtm_sks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_rtm_id')->constrained('ami_rtms')->cascadeOnDelete();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ami_rtm_id', 'ami_sk_auditor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_rtm_sks');
    }
};
