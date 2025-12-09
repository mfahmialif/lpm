<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->smallInteger('year');
            $table->string('name');
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->enum('result', ['A', 'B', 'C', 'Not Accredited'])->default('Not Accredited');
            $table->string('result_description')->nullable();
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
        Schema::dropIfExists('accreditations');
    }
};
