<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DosenCompetencyTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'kode_dosen' => 'DSN001',
                'kode_kompetensi' => 'COMP001',
                'nama_prodi' => 'S1-Sejarah Peradapan Islam',
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'kode_dosen',
            'kode_kompetensi',
            'nama_prodi',
        ];
    }
}
