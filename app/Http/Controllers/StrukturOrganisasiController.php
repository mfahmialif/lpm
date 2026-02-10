<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrukturOrganisasi;
use App\Models\PeriodeLpm;
use App\Models\AnggotaStrukturOrganisasi;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        // Get the active period's structure
        $aktivPeriode = PeriodeLpm::where('status', 'aktif')->first();
        $struktur = null;
        $anggotaNames = [];

        if ($aktivPeriode) {
            $struktur = StrukturOrganisasi::where('periode_lpm_id', $aktivPeriode->id)->first();

            // Get anggota names from JSON IDs
            if ($struktur && $struktur->anggota) {
                $anggotaIds = json_decode($struktur->anggota, true) ?? [];
                if (!empty($anggotaIds)) {
                    $anggotaNames = AnggotaStrukturOrganisasi::whereIn('id', $anggotaIds)
                        ->pluck('nama')
                        ->toArray();
                }
            }
        }

        return view('struktur_organisasi.index', compact('struktur', 'aktivPeriode', 'anggotaNames'));
    }
}
