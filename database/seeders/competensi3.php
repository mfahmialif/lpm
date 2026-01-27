<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class competensi3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $mpi = [
            "Manajemen Pendidikan Islam",
            "Manajemen Madrasah",
            "Manajemen PIAUD dan Raudhatul Atfal (RA)",
            "Manajemen Perpustakaan dan Informasi pada Lembaga Pendidikan Islam",
            "Manajemen Kurikulum Lembaga Pendis",
            "Manajemen Pembelajaran Pendis",
            "Manajemen SDM Lembaga Pendidikan Islam",
            "Manajemen Sarpras Lembaga Pendidikan Islam",
            "Manajemen Pembiayaan Pendis",
            "Manajemen Mutu Pendidikan Pendis",
            "Manajemen Strategik Pendidikan Islam",
            "Manajemen Peserta Didik Lembaga Pendidikan Islam",
            "Kepemimpinan Lembaga Pendidikan Islam",
            "Kebijakan dan Inovasi Pendidikan Islam",
            "Manajemen Humas dan Kerjasama Pendis",
            "Manajemen Laboratorium Pendidikan Islam",
            "Manajemen Pemasaran Jasa Pendidikan Islam",
            "Manajemen Pendidikan dan Pelatihan (DIKLAT) Pendis",
            "Manajemen Perkantoran dan Kearsipan Pendis",
            "Manajemen Pesantren",
            "Manajemen Sekolah dan Madrasah",
            "Desain Program Pendidikan Islam",
            "Kajian Perilaku Organisasi Lembaga Pendidikan Islam",
            "Sistem Informasi Manajemen (SIM) Pendis",
            "Manajemen dan Organisasi Pendis",
            "Kajian Manajemen Bisnis dan Kewirausahaan Pendidikan Islam",
            "Filsafat Manajemen Pendidikan Islam",
            "Etika Komunikasi Organisasi Pendis",
            "Manajemen Supervisi Pendidikan Islam",
            "Manajemen Perguruan Tinggi Islam",
            "Manajemen Mutu Terpadu Pendidikan Islam",
            "Manajemen Strategis Pendidikan Islam",
            "Metode Penelitian Manajemen Pendidikan Islam",
            "Aplikasi Komputer Manajemen Pendidikan Islam",
            "Sistem Manajemen Lingkungan di Lembaga Pendidikan Islam",
            "Manajemen Lembaga Pendidikan Islam",
            "Politik Pendidikan Islam",
            "Tinggi Keagamaan",
            "Manajemen Layanan Khusus di Lembaga Pendidikan Islam",
            "Manajemen Perubahan di LPI",
            "Tafsir & Hadits Manajemen Pendidikan",
            "Manajemen Sumber Daya Manusia di LPI",
            "Manajemen Masjid",
            "Manajemen Pendidikan dan Pelatihan pada Lembaga Pendidikan Islam",
            "Manajemen Akreditasi Lembaga Pendidikan Islam",
            "Komunikasi Organisasi di Lembaga Pendidikan Islam",
            "Digital Marketing pada Lembaga Pendidikan Islam",
            "Manajemen Kelas pada Lembaga Pendidikan Islam",
            "Metodologi Penelitian Kualitatif di LPI",
            "Metodologi Penelitian Kuantitatif di LPI",
            "Sistem Informasi Manajemen LPI",
            "Aplikasi Teknologi Multimedia di LPI",
            "Organisasi Informasi Perpustakaan di LPI",
            "Otomasi Perpustakaan di LPI"
        ];

        $data = [];

        foreach ($mpi as $nama) {
            $data[] = [
                'nama'       => $nama,
                'kode' => random_int(100000, 999999),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('competencies')->insert($data);
    }
}
