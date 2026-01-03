<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi7 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $kpi = [
            // Dasar
            "Penyiaran Islam",
            "Kehumasan Islam",
            "Komunikasi Islam",

            // Metodologi & Kajian
            "Metodologi Penelitian KPI",
            "Komunikasi Lintas Agama dan Budaya",
            "Media Komunikasi dan Penyiaran Islam",
            "Hukum dan Etika Penyiaran Islam",

            // Produksi & Digital
            "Produksi Penyiaran Islam",
            "Komunikasi dan Penyiaran Islam Digital",
            "Komunikasi dan Sistem Informasi Islam",
            "Teknologi Komunikasi dan Penyiaran Islam",
            "Entrepreneurship Komunikasi dan Penyiaran Islam",
            "Filantropi Penyiaran Islam",

            // Dakwah & Filsafat
            "Filsafat dan Teori-teori Dakwah",
            "Teknologi Informasi dan Komunikasi",
            "Komunikasi Massa Islam",
            "Komunikasi Islam",
            "Pengantar Jurnalistik",
            "Psikologi Dakwah",
            "Filsafat-Etika Komunikasi dan Islam",
            "Pengantar Broadcasting",

            // Sosial & Media
            "Sosiologi Komunikasi",
            "Desain Komunikasi Visual",
            "Manajemen Redaksi",
            "MPK Kuantitatif",
            "Hukum dan Etika Penyiaran dan Jurnalistik",
            "Psikologi Komunikasi",
            "Komunikasi Profetik",
            "Teknologi dan Media Baru",
            "Komunikasi Kelompok dan Organisasi Islam",

            // Dakwah & Politik
            "Dakwah dan Politik",
            "Dakwah dan Moderasi Beragama",
            "Komunikasi Lintas Agama dan Budaya",
            "Komunikasi Politik dan Opini Publik Islam"
        ];

        foreach ($kpi as $nama) {
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
