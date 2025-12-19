<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prodi_units')->insert([
            [
                'nama' => 'S1-Sejarah Peradaban Islam',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Manajemen Pendidikan Islam (Magister)',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S3-Pendidikan Agama Islam (Doktoral)',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Ekonomi Syariah',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Bimbingan dan Konseling Islam',
                'keterangan' => 'xxxxxxxxxxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Komunikasi dan Penyiaran Islam',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Hukum Keluarga Islam',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Pendidikan Agama Islam',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Manajemen Pendidikan Islam',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Pendidikan Bahasa Arab (Magister)',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Pendidikan Bahasa Arab',
                'keterangan' => 'xxxxxxxxxxxx'
            ],
            [
                'nama' => 'S2-Pendidikan Agama Islam (Magister)',
                'keterangan' => 'xxxxxxxxxxxxx'
            ],
            [
                'nama' => 'S1-Hukum Keluarga Islam (Double Degree)',
                'keterangan' => 'xxxxxxxxxxxxx'
            ]
        ]);
    }
}
