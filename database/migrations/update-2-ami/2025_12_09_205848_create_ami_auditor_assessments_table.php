<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmiAuditorAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ami_auditor_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_id')->constrained('ami_periods');
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->text('assessment_guide')->nullable();
            $table->string('auditee_name')->nullable();
            $table->string('document')->nullable();
            $table->enum('status', ['y', 'n'])->default('n');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ami_auditor_assessments');
    }
}
