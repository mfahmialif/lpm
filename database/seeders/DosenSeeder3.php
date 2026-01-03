<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder3 extends Seeder
{
    public function run()
    {
        // Data sudah tercover di DosenSeeder2, dikosongkan untuk menghindari duplikasi
        $data = [];

        foreach ($data as $row) {
            DB::table('mst_dosen')->insert([
                'id' => $row[0],
                'prodi_id' => $row[1],
                'kode' => $row[2],
                'nidn' => $row[3],
                'nama' => $row[4],
                'gelar_depan' => $row[5] ?: null,
                'gelar_belakang' => $row[6] ?: null,
                'jk_id' => $row[7],
                'tempat_lahir' => $row[8],
                'tanggal_lahir' => $row[9],
                'alamat' => $row[10],
                'kota_id' => $row[11],
                'email' => $row[12],
                'hp' => $row[13],
                'dosen_status_id' => $row[14],
                'status_dosen_tetap_id' => $row[15],
                'user_id' => $row[16],
                'created_at' => $row[17],
                'updated_at' => $row[18],
            ]);
        }
    }
}
