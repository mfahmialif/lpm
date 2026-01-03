<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi8 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $mhu = [
            "Studi Kebijakan Haji dan Umrah",
            "Filsafat dan Etika Haji",
            "Sejarah Haji dan Umrah",
            "Sosio-Antropologi Haji dan Umrah",
            "Studi Kebijakan Kesehatan Haji",
            "Bimbingan Haji dan Umrah",
            "Manajemen Penyelenggaraan Haji dan Umrah",
            "Manajemen SDM Haji dan Umrah",
            "Psikologi Haji dan Umrah",
            "Manajemen Pemasaran Haji, Umrah, dan Wisata Religi",
            "Microguiding Haji dan Umrah",
            "Manajemen Lembaga Haji dan Umrah",
            "Media Bimbingan Manasik Haji dan Umrah",
            "Sistem Informasi Manajemen Haji dan Umrah",
            "Wisata Religi"
        ];

        foreach ($mhu as $nama) {
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
