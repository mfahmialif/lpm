<?php

namespace App\Exports;

use App\Models\AmiIndikator;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndikatorExport implements FromArray, WithHeadings, WithStyles
{
    protected $skId;

    public function __construct($skId)
    {
        $this->skId = $skId;
    }

    public function array(): array
    {
        $indikators = AmiIndikator::with('rubrikSkors')
            ->where('ami_sk_auditor_id', $this->skId)
            ->orderBy('urutan')
            ->get();

        $rows = [];
        foreach ($indikators as $ind) {
            $row = [
                'kode' => $ind->kode,
                'pertanyaan' => $ind->pertanyaan,
                'narasi_evaluasi_diri' => $ind->narasi_evaluasi_diri ?? '',
                'urutan' => $ind->urutan,
                'is_active' => $ind->is_active ? 1 : 0,
            ];

            // Flatten rubrik into columns (max 5 rubrik)
            for ($i = 0; $i < 5; $i++) {
                $rubrik = $ind->rubrikSkors->get($i);
                $row['rubrik_skor_' . ($i + 1)] = $rubrik ? $rubrik->skor : '';
                $row['rubrik_deskripsi_' . ($i + 1)] = $rubrik ? $rubrik->deskripsi : '';
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = [
            'kode',
            'pertanyaan',
            'narasi_evaluasi_diri',
            'urutan',
            'is_active',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $headings[] = 'rubrik_skor_' . $i;
            $headings[] = 'rubrik_deskripsi_' . $i;
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
