<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ami_sk_auditor_anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ami_sk_auditor_id')->constrained('ami_sk_auditors')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('peran', ['auditor_anggota', 'auditee']);
            $table->timestamps();

            $table->unique(['ami_sk_auditor_id', 'user_id', 'peran'], 'sk_user_peran_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ami_sk_auditor_anggotas');
    }
};
