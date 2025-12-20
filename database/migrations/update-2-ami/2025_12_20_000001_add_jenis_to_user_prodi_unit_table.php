<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisToUserProdiUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_prodi_unit', function (Blueprint $table) {
            $table->enum('jenis', ['editor', 'audity'])->default('editor')->after('prodi_unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_prodi_unit', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
}
