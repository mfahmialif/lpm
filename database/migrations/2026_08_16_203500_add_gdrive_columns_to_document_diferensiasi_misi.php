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
        Schema::table('document_diferensiasi_misi', function (Blueprint $table) {
            if (!Schema::hasColumn('document_diferensiasi_misi', 'gdrive_file_id')) {
                $table->string('gdrive_file_id')->nullable()->after('path');
            }
            if (!Schema::hasColumn('document_diferensiasi_misi', 'upload_status')) {
                $table->enum('upload_status', ['pending', 'uploaded', 'failed'])->default('pending')->after('gdrive_file_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_diferensiasi_misi', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('document_diferensiasi_misi', 'gdrive_file_id')) {
                $columnsToDrop[] = 'gdrive_file_id';
            }
            if (Schema::hasColumn('document_diferensiasi_misi', 'upload_status')) {
                $columnsToDrop[] = 'upload_status';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
