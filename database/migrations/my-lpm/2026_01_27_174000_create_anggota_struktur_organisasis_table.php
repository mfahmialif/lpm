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
        // Moved to 2026_01_27_200000_create_struktur_organisasis_table.php (if needed)
        // But the new structure uses a text column in main table, so this table is obsolete.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_struktur_organisasis');
    }
};
