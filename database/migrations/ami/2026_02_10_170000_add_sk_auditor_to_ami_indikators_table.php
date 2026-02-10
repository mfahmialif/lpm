<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ami_indikators', function (Blueprint $table) {
            $table->foreignId('ami_sk_auditor_id')->after('id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->text('narasi_evaluasi_diri')->nullable()->after('pertanyaan');
            $table->dropUnique(['kode']);
            $table->dropColumn('jenis_unit');
            $table->unique(['ami_sk_auditor_id', 'kode']);
        });
    }

    public function down()
    {
        Schema::table('ami_indikators', function (Blueprint $table) {
            $table->dropUnique(['ami_sk_auditor_id', 'kode']);
            $table->dropForeign(['ami_sk_auditor_id']);
            $table->dropColumn('ami_sk_auditor_id');
            $table->dropColumn('narasi_evaluasi_diri');
            $table->string('kode')->unique()->change();
            $table->enum('jenis_unit', ['Prodi', 'Fakultas', 'Institusi', 'Semua'])->default('Semua');
        });
    }
};
