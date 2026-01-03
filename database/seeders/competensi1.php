<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $mataKuliah = [
            "Hukum Keluarga Islam di Indonesia",
            "Fiqh Mawaris",
            "Peradilan Agama",
            "Hukum Perdata Islam di Indonesia",
            "Hukum Acara Perdata Islam",
            "Fiqh Munakahat",
            "Ilmu Falak",
            "Ushul Fiqh",
            "Mediasi Yudisial di Peradilan Agama",
            "Konsultasi Keluarga Islam",
            "Kepenghuluan",
            "Hukum Kekerasan Rumah Tangga",
            "Mediasi Non-Yudisial Keluarga Islam",
            "Psikologi Keluarga Islam",
            "Advokasi Keluarga Islam",
            "Sejarah Perkembangan Hukum Islam",
            "Filsafat Hukum Islam",
            "Pemikiran Hukum Keluarga Islam",
            "Metode Penelitian Hukum Keluarga",
            "Ilmu Fiqh",
            "Qawaid Fiqhiyyah",
            "Perbandingan Hukum Keluarga Islam",
            "Masail Fiqhiyyah",
            "Perbandingan Sistem Hukum Keluarga",
            "Legal Drafting HKI",
            "Pemikiran Modern Hukum Keluarga",
            "Syariah, HAM, dan Gender dalam HKI",
            "Hukum Adat dalam HKI",
            "Fiqh Ibadah",
            "Sosiologi Hukum Keluarga Islam",
            "Antropologi Hukum Keluarga Islam",
            "Maqashid al-Syariah",
            "Hukum Acara Peradilan Agama",
            "Fiqh dan Manajemen Zakat di Indonesia",
            "Fiqh dan Manajemen Wakaf di Indonesia",
            "Sejarah Peradilan Islam",
            "Peradilan Agama di Indonesia",
            "Manajemen dan Administrasi Pengadilan Agama",
            "Manajemen dan Administrasi Kantor Urusan Agama",
            "Studi Naskah Hukum Islam"
        ];

        $data = [];

        foreach ($mataKuliah as $nama) {
            $data[] = [
                'kode'       => random_int(100000, 999999),
                'nama'       => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('competencies')->insert($data);
    }
}
