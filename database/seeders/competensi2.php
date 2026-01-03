<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $PAI = [
            "Ilmu Pendidikan Islam",
            "Pembelajaran Pendidikan Agama Islam",
            "Pemikiran Pendidikan Islam",
            "Filsafat Pendidikan Islam",
            "Sejarah Pendidikan Islam",
            "Pembelajaran Fiqh",
            "Pembelajaran Al-Qur'an Hadits",
            "Pembelajaran Aqidah Akhlak",
            "Pembelajaran Sejarah Kebudayaan Islam",
            "Psikologi Pendidikan Islam",
            "Metodologi Penelitian Pendidikan Islam",
            "PAI Kontemporer",
            "Teknologi Pembelajaran PAI",
            "Pengembangan Evaluasi Pembelajaran PAI berbasis IT",
            "Pengembangan Kurikulum PAI",
            "Perencanaan Pembelajaran PAI",
            "Kajian Materi PAI di Sekolah",
            "Pengembangan Bahan Ajar PAI",
            "Metodologi Pengajaran PAI",
            "Teknologi dan Media Pembelajaran PAI",
            "Evaluasi dan Penilaian Pembelajaran PAI",
            "Perbandingan Pendidikan Islam",
            "Pendidikan Islam Multikultural dan Moderasi Beragama",
            "Metode & Strategi Pembelajaran PAI",
            "Kajian Materi Al-Qur'an dan Hadis di Madrasah",
            "Kajian Materi Aqidah dan Akhlak di Madrasah",
            "Kajian Materi Fiqh di Madrasah",
            "Kajian Materi Sejarah Kebudayaan Islam di Madrasah",
            "Teori-Teori Pembelajaran dalam Islam",
            "Pendidikan Nilai dan Karakter Islami",
            "Profesionalisme dan Kompetensi Guru PAI",
            "Kebijakan Pendidikan Islam",
            "Praktik Pembelajaran PAI",
            "Pendidikan Ekologi dalam PAI",
            "Model-Model Pembelajaran PAI",
            "Etika Profesi Guru PAI",
            "Sosiologi Pendidikan Islam",
            "Pembelajaran PAI Inklusi",
            "Analisis Pengelolaan Kelas PAI"
        ];

        $data = [];

        foreach ($PAI as $nama) {
            $data[] = [
                'kode' => random_int(100000, 999999),
                'nama'       => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('competencies')->insert($data);
    }
}
