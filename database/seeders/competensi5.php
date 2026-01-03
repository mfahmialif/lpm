<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi5 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $spi = [
            // Dasar Sejarah Islam
            "Ilmu Sejarah Islam",
            "Ilmu Budaya Islam",
            "Filsafat Sejarah Islam",
            "Sejarah Peradaban Islam",

            // Sejarah & Kebudayaan Islam Global
            "Sejarah Kebudayaan Islam",
            "Sejarah Pemikiran Islam",
            "Sejarah Islam Klasik",
            "Sejarah Islam Abad Pertengahan",
            "Sejarah Islam Abad Modern dan Kontemporer",
            "Sirah Nabawiyah",
            "Sejarah Politik Islam",
            "Sejarah Kota-Kota Islam",
            "Sinema Sejarah Islam",
            "Sejarah Seni Islam",
            "Sejarah Ekonomi di Dunia Islam",
            "Sejarah Muslim Minoritas",
            "Sejarah Transmigrasi Masyarakat Muslim",
            "Pariwisata Sejarah Islam",
            "Sejarah Islam Kawasan",
            "Sejarah Kawasan Kebudayaan Islam di Asia Tenggara",

            // Sejarah Islam Nusantara & Melayu
            "Sejarah Sosial Intelektual Islam di Nusantara",
            "Sejarah Kebudayaan Melayu",
            "Sejarah Maritim di Nusantara",

            // Sejarah Islam Indonesia
            "Sejarah Islam Indonesia Masa Kesultanan",
            "Sejarah Islam Indonesia Masa Kolonial",
            "Sejarah Islam Indonesia Masa Pergerakan Nasional",
            "Sejarah Islam di Indonesia Masa Modern",
            "Sejarah Islam Indonesia Masa Kemerdekaan",

            // Sejarah Sosial & Lokal
            "Sejarah Publik pada Masyarakat Islam",
            "Sejarah dan Kebudayaan Islam Lokal",
            "Sejarah Pesantren",
            "Bahasa Sumber Sejarah Islam",

            // Metodologi & Kajian Sejarah
            "Bibliografi Sejarah dan Peradaban Islam",
            "Historiografi Islam",
            "Historiografi Islam Indonesia",
            "Teori dan Metodologi Sejarah",
            "Metode Penelitian Sejarah Islam",
            "Arkeologi Islam",
            "Filologi Islam Nusantara",
            "Studi Naskah Sejarah Islam",
            "Sejarah Pers Islam"
        ];

        foreach ($spi as $nama) {
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
