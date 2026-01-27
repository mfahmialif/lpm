<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $pba = [
            // Dasar & Umum
            "Pembelajaran Bahasa Arab",
            "Pembelajaran Tarjamah",
            "Bahasa Arab untuk Anak Usia Dini",
            "Evaluasi Pembelajaran Bahasa Arab",
            "Psikologi Pembelajaran Bahasa Arab",
            "Ilmu Bahasa Arab",
            "Kurikulum Pembelajaran Bahasa Arab",
            "Pengembangan Bahan Ajar Bahasa Arab",

            // Manajemen & Metodologi
            "Manajemen Pembelajaran Bahasa Arab",
            "Teknologi dan Media Pembelajaran Bahasa Arab",
            "Model Pembelajaran Bahasa Arab",
            "Strategi Pembelajaran Bahasa Arab",
            "Metode Pembelajaran Bahasa Arab",

            // Maharah & Ilmu Bahasa
            "Pembelajaran Balaghah",
            "Pembelajaran Ilmu Aswat",
            "Pembelajaran Istima'",
            "Pembelajaran Kalam",
            "Pembelajaran Qiraah",
            "Pembelajaran Kitabah",
            "Pembelajaran Kaligrafi Arab",
            "Pembelajaran Morfologi (Sharaf)",
            "Pembelajaran Sintaksis Arab (Nahwu)",

            // Kurikulum & Penelitian
            "Metode Penelitian Pendidikan Bahasa Arab",
            "Pengembangan Kurikulum Bahasa Arab",
            "Pembelajaran Linguistik Arab",
            "Pengembangan Bahan Ajar Bahasa Arab",
            "Desain Pembelajaran Bahasa Arab",

            // Kajian Madrasah
            "Kajian Materi Bahasa Arab di Madrasah Tsanawiyah",
            "Kajian Materi Bahasa Arab di Madrasah Aliyah",

            // Ilmu Bahasa Murni
            "Ilmu Nahwu",
            "Ilmu Sharaf",
            "Teori Belajar dan Pembelajaran Bahasa Arab",

            // Bahasa Arab untuk Bidang Keilmuan
            "Bahasa Arab untuk Hukum",
            "Bahasa Arab untuk Psikologi",
            "Bahasa Arab untuk Sains",
            "Bahasa Arab untuk Ekonomi",
            "Bahasa Arab untuk Kesehatan",
            "Bahasa Arab untuk Bisnis",
            "Bahasa Arab untuk Kepariwisataan",
            "Bahasa Arab untuk Dakwah",
            "Bahasa Arab untuk Studi Islam",
            "Bahasa Arab untuk Studi Agama-Agama",
            "Bahasa Arab untuk Teknologi Informasi",
            "Bahasa Arab untuk Sosial Politik",
            "Bahasa Arab untuk Seni",
            "Bahasa Arab untuk Edupreneurship",
            "Pembelajaran Bahasa untuk Non-Arab",

            // Qawaid & Tarjamah
            "Qawaid al-Lughah al-Arabiyah wa Ta'limuha",
            "at-Takhliyah ar-Raqmi wa Adz-Dzaka' ash-Shina'iy fi TLA",

            // Metodologi Arab (Arab Full)
            "Al-Lisaniyat wa Tathbiquha fi Ta'lim al-Lughah al-Arabiyah",
            "an-Nushush al-Arabiyah al-Qadimah wa al-Haditsah wa Tarjamatuha",
            "Manahij al-Bahtsi fi Ta'lim al-Lughah al-Arabiyah wa al-Kitabah al-Akademiyah",
            "Tathwir al-Manahij wa al-Mawad fi Ta'lim al-Lughah al-Arabiyah",
            "Thuruq Ta'lim al-Lughah al-Arabiyah wa Taqwimuha",
            "Idarat Ta'lim al-Lughah al-Ajnabiyah",
            "Tathwir Kafaah Muallimi al-Lughah al-Arabiyah",

            // Psikolinguistik & Kemahiran
            "Ilm al-Lughah an-Nafsy wa al-Ijtima'iy",
            "Al-'Arabiyyah al-Takamuliyyah",
            "Istima' Hiwarat wa al-Aflam",
            "Istima' Muhadharat wa al-Akhbar",
            "Al-Hiwarat wa al-Masrahiyyat",
            "Al-Khutbah wa al-Munazharat",
            "Al-Qira'ah al-Jahriyah",
            "Al-Qira'ah as-Shamitah",
            "Al-Insya' al-Hurr",
            "Al-Insya' al-Akademiy",

            // Qawaid
            "Qawaid al-Imla'",
            "As-Sharf al-Ibtida'iy",
            "As-Sharf al-Mutaqaddim",
            "An-Nahw al-Ibtida'iy",
            "An-Nahw al-Mutaqaddim",
            "Ilm al-Ma'ani",
            "Ilm al-Bayan",
            "Ilm al-Badi'",
            "Ilm al-Lughah",
            "Ilm al-Aswat",
            "Ilm ad-Dilalah wa al-Ma'ajim",

            // Manajemen & Media Pembelajaran Arab
            "Manahij al-Bahtsi fi Ta'lim al-Lughah al-Arabiyah",
            "Manahij al-Lughah al-Arabiyah fi al-Madaris",
            "Turuq Ta'lim al-Lughah al-Arabiyah",
            "Wasail Ta'lim al-Lughah al-Arabiyah",
            "Taqwim Ta'lim al-Lughah al-Arabiyah",
            "Tashmim Ta'lim al-Lughah al-Arabiyah",
            "At-Ta'lim al-Musagghar",
            "Wasail at-Ta'lim ar-Raqmiyyah fi Ta'lim al-Lughah al-Arabiyah",
            "Al-Adab al-Lughawiy fi Ta'lim al-Lughah al-Arabiyah",
            "Ad-Dhakaa al-Istina'iy fi Ta'lim al-Lughah al-Arabiyah",

            // Khat & Tarjamah
            "Al-Khat al-'Araby al-Ibtida'iy",
            "Al-Khat al-'Araby al-Mutaqaddim",
            "Zakhrafat al-Khat al-'Araby",

            // Tarjamah
            "Mabadi' at-Tarjamah al-Fauriyyah",
            "At-Tarjamah al-Fauriyyah min al-'Arabiyyah ila al-Indunisiyyah",
            "At-Tarjamah al-Fauriyyah min al-Indunisiyyah ila al-'Arabiyyah",

            // Lain-lain
            "Fiqh Lughah",
            "Tarikh al-Adab wa al-Hadharah",
            "Bahasa Arab Jurnalistik",
            "Ilmu al-Lughah an-Nafsy",
            "Ilmu Dilalah wa al-Ma'ajim"
        ];

        foreach ($pba as $nama) {
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
