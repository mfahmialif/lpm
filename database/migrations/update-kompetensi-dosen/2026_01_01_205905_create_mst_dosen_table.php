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
        Schema::create('mst_dosen', function (Blueprint $table) {
            $table->id();

            // Relasi Akademik
            // Menggunakan unsignedBigInteger jika tabel referensi belum ada saat migrasi dijalankan
            // Jika tabel sudah ada, bisa diganti menjadi foreignId()->constrained()

            $table->unsignedBigInteger('prodi_id')->nullable();
            // $table->foreignId('prodi_id')->nullable()->constrained('prodi')->nullOnDelete();

            $table->unsignedBigInteger('jk_id')->nullable();
            // $table->foreignId('jk_id')->nullable()->constrained('jenis_kelamin')->nullOnDelete();

            $table->unsignedBigInteger('kota_id')->nullable();
            // $table->foreignId('kota_id')->nullable()->constrained('kota')->nullOnDelete();

            $table->unsignedBigInteger('dosen_status_id')->nullable();
            // $table->foreignId('dosen_status_id')->nullable()->constrained('dosen_status')->nullOnDelete();

            $table->unsignedBigInteger('status_dosen_tetap_id')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreignId('status_dosen_tetap_id')->nullable()->constrained('status_dosen_tetap')->nullOnDelete();

            // User biasanya sudah ada
            // $table->foreignId('user_id')->nullable()
            //     ->constrained('users')
            //     ->nullOnDelete();

            // Identitas Dosen
            $table->string('kode', 10)->nullable();
            $table->string('nidn', 50)->nullable()->unique();
            $table->string('nama');
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();

            // Data Personal
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();

            // Kontak
            $table->string('email', 100)->nullable()->unique();
            $table->string('hp', 100)->nullable();

            // Audit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_dosen');
    }
};
