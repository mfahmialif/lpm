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
        Schema::create('ami_assessment_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_auditor_assessment_id')->constrained('ami_auditor_assessments')->onDelete('cascade');
            $table->foreignId('ami_assessment_score_id')->constrained('ami_assessment_scores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ami_assessment_selections');
    }
};
