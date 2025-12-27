<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ami_assessment_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_auditor_assessment_id')->constrained('ami_auditor_assessments')->onDelete('cascade');
            $table->text('indicator');
            $table->timestamps();
        });

        Schema::create('ami_assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_assessment_indicator_id')->constrained('ami_assessment_indicators')->onDelete('cascade');
            $table->integer('score');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ami_assessment_scores');
        Schema::dropIfExists('ami_assessment_indicators');
    }
};
