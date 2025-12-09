<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmiFinalResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ami_final_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_period_id')->constrained('ami_periods');
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->string('end_score_spme')->nullable();
            $table->string('score_ikt')->nullable();
            $table->string('end_score_ami')->nullable();
            $table->string('rank_ami')->nullable();
            $table->enum('accreditation_status', ['A', 'B', 'C', 'Not Accredited'])->default('Not Accredited');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('ami_final_results');
    }
}
