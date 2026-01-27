<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi6 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $bki = [
            "Ilmu Bimbingan Konseling Islam",
            "Dasar-dasar BKI",
            "Komunikasi Konseling Islam",
            "Kesehatan Mental dalam Islam",
            "Psikologi Kepribadian dalam Islam",
            "Mikro Konseling Islam",
            "Bimbingan dan Konseling Keluarga Islam",
            "Media Bimbingan dan Konseling Islam",
            "Psikologi Konseling Islam",
            "Bimbingan dan Konseling Islam Anak & Remaja",
            "Bimbingan dan Konseling Pesantren",
            "Konseling Lintas Agama dan Budaya",
            "Kode Etik Konseling Islam",
            "BKI Dewasa dan Lansia",
            "BKI untuk Disabilitas"
        ];

        foreach ($bki as $nama) {
            DB::table('competencies')->updateOrInsert(
                ['nama' => $nama],
                ['kode' => random_int(100000, 999999)],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
