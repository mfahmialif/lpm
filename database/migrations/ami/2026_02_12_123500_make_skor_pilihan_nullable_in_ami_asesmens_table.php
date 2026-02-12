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
        Schema::table('ami_asesmens', function (Blueprint $table) {
            $table->string('skor_pilihan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ami_asesmens', function (Blueprint $table) {
            $table->string('skor_pilihan')->nullable(false)->change();
        });
    }
};
