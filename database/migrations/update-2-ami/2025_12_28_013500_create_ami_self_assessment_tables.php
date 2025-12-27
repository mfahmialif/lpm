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
        Schema::create('ami_self_assessment_indicators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ami_self_assessment_id');
            $table->foreign('ami_self_assessment_id', 'ami_self_asmt_ind_fk')->references('id')->on('ami_self_assessments')->onDelete('cascade');
            $table->text('indicator');
            $table->timestamps();
        });

        Schema::create('ami_self_assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ami_self_assessment_indicator_id');
            $table->foreign('ami_self_assessment_indicator_id', 'ami_self_asmt_scr_ind_fk')->references('id')->on('ami_self_assessment_indicators')->onDelete('cascade');
            $table->integer('score');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('ami_self_assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ami_self_assessment_id');
            $table->foreign('ami_self_assessment_id', 'ami_self_asmt_resp_fk')->references('id')->on('ami_self_assessments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestamps();
        });

        Schema::create('ami_self_assessment_selections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ami_self_assessment_id');
            $table->foreign('ami_self_assessment_id', 'ami_self_asmt_sel_asmt_fk')->references('id')->on('ami_self_assessments')->onDelete('cascade');
            $table->unsignedBigInteger('ami_self_assessment_score_id');
            $table->foreign('ami_self_assessment_score_id', 'ami_self_asmt_sel_scr_fk')->references('id')->on('ami_self_assessment_scores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ami_self_assessment_selections');
        Schema::dropIfExists('ami_self_assessment_responses');
        Schema::dropIfExists('ami_self_assessment_scores');
        Schema::dropIfExists('ami_self_assessment_indicators');
    }
};
