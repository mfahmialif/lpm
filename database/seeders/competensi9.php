<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi9 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $esy = [
            // Dasar Ekonomi Syariah
            "Ekonomi Syari'ah",
            "Asuransi Syari'ah",
            "Fiqh Muamalah",
            "Ekonomi Makro Syari'ah",
            "Ekonomi Mikro Syari'ah",
            "Sejarah Pemikiran Ekonomi Islam",
            "Ekonomi Publik Syari'ah",
            "Pasar Modal dan Reksadana Syari'ah",
            "Ekonomi dan Keuangan Islam",

            // Lembaga & Sistem Keuangan
            "Etika Ekonomi dan Bisnis Islam",
            "Bank dan Lembaga Keuangan Syari'ah",
            "Ekonomi Pendidikan Islam",
            "Pemikiran Ekonomi Islam",
            "Islamic Digital Economy",
            "Technopreneur Syari'ah",
            "Ekonomi Zakat dan Wakaf",
            "Ekonomi Pembangunan Islam",
            "Ekonomi Keuangan Syari'ah",
            "Ekonomi Pesantren",

            // Fiqh & Maqashid
            "Maqasid Syari'ah fi Muamalah",
            "Ekonomi Pembangunan Syari'ah",
            "Ekonomi Moneter Islam",
            "Filantropi Islam",
            "Ekonomi Koperasi Syari'ah",
            "Ekonomi Sumber Daya Insani",
            "Ekonomi Politik dan Hukum Ekonomi Islam",

            // Bisnis & Industri Halal
            "Etika Bisnis Islam dan Profesi",
            "Ekonomi Kreatif dan Industri Halal",
            "Ushul Fiqh dan Qawaid Fiqhiyyah fi Muamalah",
            "Ekonomi Kreatif Syari'ah",
            "Ekonomi Digital Syari'ah",
            "Pariwisata Halal",
            "Ekonomi Sumber Daya Alam dan Lingkungan dalam Islam"
        ];

        foreach ($esy as $nama) {
            DB::table('competencies')->updateOrInsert(
                ['nama' => $nama],
                ['kode' => rand(100000, 999999)],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
