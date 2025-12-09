<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prodis')->insert([
            [
                'nama' => 'S1-Sejarah Peradaban Islam',
                'kaprodi' => 'Sahri, M.H.I',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Adab',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Manajemen Pendidikan Islam (Magister)',
                'kaprodi' => 'Dr. Sodikin, M.Pd I',
                'strata' => 'S2',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S3-Pendidikan Agama Islam (Doktoral)',
                'kaprodi' => 'Dr. Moch Romli',
                'strata' => 'S3',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Ekonomi Syariah',
                'kaprodi' => 'Ahmad Misbah, M.Pd.I',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Syariah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Bimbingan dan Konseling Islam',
                'kaprodi' => 'Mohamad Syafiq, S.Psi., M.Pd.',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Dakwah',
                'nohp' => 'xxxxxxxxxxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Komunikasi dan Penyiaran Islam',
                'kaprodi' => 'Muhammad Iqbal Dewantara, M.Pd.I',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Dakwah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Hukum Keluarga Islam',
                'kaprodi' => 'Abdul Kadir, M.Ag.',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Syariah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Pendidikan Agama Islam',
                'kaprodi' => 'Drs. Junaidi, M.Pd.',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Manajemen Pendidikan Islam',
                'kaprodi' => 'Dr. Muhammad Ubaidillah,  M.Pd',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Pendidikan Bahasa Arab (Magister)',
                'kaprodi' => 'Muhammad Shofi, M.Pd',
                'strata' => 'S2',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Pendidikan Bahasa Arab',
                'kaprodi' => 'Moh. Tohiri Habib, M.Pd.',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Pendidikan Agama Islam (Magister)',
                'kaprodi' => 'Dr. Asep Rahmatullah, M.Pd.',
                'strata' => 'S2',
                'fakultas' => 'Fakultas Tarbiyah',
                'nohp' => 'xxxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Hukum Keluarga Islam (Double Degree)',
                'kaprodi' => 'Abdul Kadir, M.Ag.',
                'strata' => 'S1',
                'fakultas' => 'Fakultas Syariah',
                'nohp' => 'xxxxxxxxxxxxx'
            ]
        ]);
    }
}
