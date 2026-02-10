<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentPedomanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_pedoman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('unit_dokument_id')->constrained('units_dokument')->onDelete('cascade');
            $table->string('no_surat')->nullable();
            $table->string('perihal')->nullable();
            $table->string('yang_mengeluarkan')->nullable();
            $table->string('path')->nullable();
            $table->enum('status', ['acc', 'tolak'])->nullable();
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
        Schema::dropIfExists('document_pedoman');
    }
}
