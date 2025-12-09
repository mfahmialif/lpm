<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccreditationRequirementEvidenceDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Get first prodi
        $prodi = DB::table('prodis')->first();

        if (! $prodi) {
            return;
        }

        // 2. Loop 10 akreditasi
        for ($i = 1; $i <= 10; $i++) {
            $accreditationId = DB::table('accreditations')->insertGetId([
                'prodi_id'           => $prodi->id,
                'year'               => 2025,
                'name'               => 'LAMDIK ' . $i,
                'status'             => 'ongoing',
                'result'             => 'Not Accredited',
                'result_description' => 'Not Accredited',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 3. Buat 10 root requirement per akreditasi
            for ($r = 1; $r <= 10; $r++) {
                $rootId = DB::table('requirements')->insertGetId([
                    'accreditation_id' => $accreditationId,
                    'parent_id'        => null,
                    'code'             => 'R' . $r,
                    'title'            => 'KRITERIA ' . $r . ': Root Requirement',
                    'description'      => 'Deskripsi root requirement ' . $r,
                    'order_index'      => $r,
                    'link'             => 'http://example.com/root-' . $r,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // 4. Buat 10 sub requirement per root
                for ($s = 1; $s <= 10; $s++) {
                    $subId = DB::table('requirements')->insertGetId([
                        'accreditation_id' => $accreditationId,
                        'parent_id'        => $rootId,
                        'code'             => 'R' . $r . '-S' . $s,
                        'title'            => 'Sub Requirement ' . $s . ' untuk Root ' . $r,
                        'description'      => 'Deskripsi sub requirement ' . $s,
                        'order_index'      => $s,
                        'link'             => 'http://example.com/root-' . $r . '/sub-' . $s,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    // 5. Buat 10 sub-sub requirement per sub
                    for ($ss = 1; $ss <= 10; $ss++) {
                        DB::table('requirements')->insert([
                            'accreditation_id' => $accreditationId,
                            'parent_id'        => $subId,
                            'code'             => 'R' . $r . '-S' . $s . '-SS' . $ss,
                            'title'            => 'Sub-Sub Requirement ' . $ss . ' untuk Sub ' . $s,
                            'description'      => 'Deskripsi sub-sub requirement ' . $ss,
                            'order_index'      => $ss,
                            'link'             => 'http://example.com/root-' . $r . '/sub-' . $s . '/subsub-' . $ss,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }
        }
    }
}
