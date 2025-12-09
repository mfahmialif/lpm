<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmiFindingResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ami_finding_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ami_categories');
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->string('assessment_question')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('ami_finding_results');
    }
}
