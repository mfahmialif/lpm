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
        Schema::table('dakung_prodi_files', function (Blueprint $table) {
            $table->string('gdrive_file_id')->nullable();
            $table->enum('upload_status', ['pending', 'uploaded', 'failed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dakung_prodi_files', function (Blueprint $table) {
            $table->dropColumn(['gdrive_file_id', 'upload_status']);
        });
    }
};
