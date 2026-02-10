<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumentImplementasiSpmiPddiktiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokument_implementasi_spmi_pddikti', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_surat');
            $table->string('path')->nullable();
            $table->string('perihal')->nullable();
            $table->enum('status', ['acc', 'tidak'])->nullable()->default(null);
            $table->string('yang_mengeluarkan')->nullable();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');
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
        Schema::dropIfExists('dokument_implementasi_spmi_pddikti');
    }
}
