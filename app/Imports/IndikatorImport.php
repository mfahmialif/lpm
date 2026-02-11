<?php

namespace App\Imports;

use App\Models\AmiIndikator;
use App\Models\AmiRubrikSkor;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class IndikatorImport implements ToArray, WithHeadingRow
{
    protected $skId;
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function __construct($skId)
    {
        $this->skId = $skId;
    }

    public function array(array $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 karena header di row 1

            $kode = trim($row['kode'] ?? '');
            $pertanyaan = trim($row['pertanyaan'] ?? '');

            if (empty($kode) || empty($pertanyaan)) {
                $this->skippedCount++;
                $this->errors[] = "Baris $rowNum: Kode atau Pertanyaan kosong, dilewati.";
                continue;
            }

            // Cek duplikat kode di SK ini
            $exists = AmiIndikator::where('ami_sk_auditor_id', $this->skId)
                ->where('kode', $kode)
                ->exists();

            if ($exists) {
                $this->skippedCount++;
                $this->errors[] = "Baris $rowNum: Kode '$kode' sudah ada, dilewati.";
                continue;
            }

            DB::beginTransaction();
            try {
                $indikator = AmiIndikator::create([
                    'ami_sk_auditor_id' => $this->skId,
                    'kode' => $kode,
                    'pertanyaan' => $pertanyaan,
                    'narasi_evaluasi_diri' => $row['narasi_evaluasi_diri'] ?? null,
                    'urutan' => (int) ($row['urutan'] ?? 0),
                    'is_active' => (int) ($row['is_active'] ?? 1),
                ]);

                // Import rubrik (max 5)
                for ($i = 1; $i <= 5; $i++) {
                    $skorKey = 'rubrik_skor_' . $i;
                    $deskKey = 'rubrik_deskripsi_' . $i;

                    $skor = $row[$skorKey] ?? null;
                    $deskripsi = $row[$deskKey] ?? null;

                    if ($skor !== null && $skor !== '' && $deskripsi !== null && $deskripsi !== '') {
                        AmiRubrikSkor::create([
                            'ami_indikator_id' => $indikator->id,
                            'skor' => (int) $skor,
                            'deskripsi' => trim($deskripsi),
                        ]);
                    }
                }

                DB::commit();
                $this->importedCount++;
            } catch (\Throwable $th) {
                DB::rollback();
                $this->errors[] = "Baris $rowNum: " . $th->getMessage();
                $this->skippedCount++;
            }
        }
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
