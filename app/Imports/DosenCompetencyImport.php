<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\DosenCompetency;
use App\Models\PeriodeAkademik;
use App\Models\Prodi;
use App\Models\Competency;
use App\Models\ProdiCompetency;
use App\Models\SkKompetensi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class DosenCompetencyImport implements ToModel, WithHeadingRow, WithEvents
{
    private static $errors = [];
    private static $importedCount = 0;

    public function model(array $row)
    {
        // Find Dosen by kode
        $dosen = Dosen::where('kode', $row['kode_dosen'])->first();
        if (!$dosen) {
            self::$errors[] = "Dosen dengan kode '{$row['kode_dosen']}' tidak ditemukan";
            return null;
        }

        // Find Prodi by nama
        $prodi = Prodi::where('nama', $row['nama_prodi'])->first();
        if (!$prodi) {
            self::$errors[] = "Prodi dengan nama '{$row['nama_prodi']}' tidak ditemukan";
            return null;
        }

        // Find Competency
        $competency = Competency::where('kode', $row['kode_kompetensi'])->first();
        if (!$competency) {
            self::$errors[] = "Kompetensi dengan kode '{$row['kode_kompetensi']}' tidak ditemukan";
            return null;
        }

        // Find ProdiCompetency
        $prodiCompetency = ProdiCompetency::where('prodi_id', $prodi->id)
            ->where('competency_id', $competency->id)
            ->first();
        if (!$prodiCompetency) {
            self::$errors[] = "Kompetensi '{$competency->nama}' tidak tersedia di prodi '{$prodi->nama}'";
            return null;
        }

        // Get active periode
        $periode = PeriodeAkademik::where('is_active', 1)->first();
        if (!$periode) $periode = PeriodeAkademik::orderBy('id', 'desc')->first();
        if (!$periode) return null;

        // Get active SK
        $sk = SkKompetensi::where('is_active', true)->orderBy('id', 'desc')->first();
        if (!$sk) $sk = SkKompetensi::orderBy('id', 'desc')->first();
        if (!$sk) return null;

        // Check Duplication
        $exists = DosenCompetency::where('prodi_competency_id', $prodiCompetency->id)
            ->where('periode_akademik_id', $periode->id)
            ->exists();

        if ($exists) {
            self::$errors[] = "Kompetensi '{$competency->nama}' untuk dosen '{$dosen->nama}' sudah ada di periode '{$periode->nama_periode}'";
            return null;
        }

        self::$importedCount++;

        return new DosenCompetency([
            'dosen_id' => $dosen->id,
            'prodi_competency_id' => $prodiCompetency->id,
            'periode_akademik_id' => $periode->id,
            'sk_kompetensi_id' => $sk->id,
            'tanggal_mulai'=>$periode->tanggal_mulai,
            'tanggal_selesai'=>$periode->tanggal_selesai,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // Store errors and success count in session for display in controller
                Session::flash('import_errors', self::$errors);
                Session::flash('imported_count', self::$importedCount);
            },
        ];
    }

    private function transformDate($value)
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat('Y-m-d', $value);
        }
    }
}
