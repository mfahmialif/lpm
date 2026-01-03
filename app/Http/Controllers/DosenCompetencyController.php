<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Manually load the service since it is in non-standard location and namespace
require_once app_path('Http/Services/Dosen.php');

use App\Services\Dosen;
use App\Models\Prodi;
use App\Models\ProdiCompetency;
use App\Models\DosenCompetency;
use App\Models\PeriodeAkademik;
use App\Models\SkKompetensi;
use Illuminate\Support\Facades\DB;

class DosenCompetencyController extends Controller
{
    public function index()
    {
        $prodis = Prodi::all();
        return view('dosen-competency.index', compact('prodis'));
    }

    public function searchDosen($search)
    {
        // Search directly from local database table mst_dosen using keys from model Dosen (App\Models\Dosen)
        // instead of external service which might be failing or empty
        $data = \App\Models\Dosen::where('nama', 'LIKE', "%$search%")
            ->orWhere('nidn', 'LIKE', "%$search%")
            ->take(30)
            ->get();

        return response()->json($data);
    }

    public function getCompetencies(Request $request)
    {
        $prodiId = $request->get('prodi_id');
        $dosenId = $request->get('dosen_id');

        // Get Active Period
        $activePeriod = PeriodeAkademik::where('is_active', true)->first();

        $assignedCompetencyIds = [];
        if ($activePeriod) {
            // Strict filtering: Get IDs of competencies taken by OTHERS.
            // Requirement: "Kompetensi hanya bisa di pilih oleh 1 dosen di prodi yang sama"
            // We filter out competencies already taken by OTHERS.
            // We allow the CURRENT lecturer to see their own taken competencies.
            $query = DosenCompetency::where('periode_akademik_id', $activePeriod->id);

            if ($dosenId) {
                $query->where('dosen_id', '!=', $dosenId);
            }

            $assignedCompetencyIds = $query->pluck('prodi_competency_id')->toArray();
        }

        $query = ProdiCompetency::where('prodi_id', $prodiId);

        if (!empty($assignedCompetencyIds)) {
            $query->whereNotIn('id', $assignedCompetencyIds);
        }

        $competencies = $query->with('competency')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'competency_name' => $item->competency->name ?? $item->competency->nama ?? '-',
                    'prodi_competency_id' => $item->id
                ];
            })
            ->values();

        return response()->json($competencies);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required', // This might come from autocomplete
            'prodi_id' => 'required',
            'competency_ids' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $activePeriod = PeriodeAkademik::where('is_active', true)->first();
            $activeSk = SkKompetensi::where('is_active', true)->first();

            if (!$activePeriod) {
                return response()->json(['status' => false, 'message' => 'Tidak ada periode akademik aktif.'], 400);
            }
            if (!$activeSk) {
                return response()->json(['status' => false, 'message' => 'Tidak ada SK Kompetensi aktif.'], 400);
            }

            foreach ($request->competency_ids as $prodiCompetencyId) {
                // Use firstOrCreate to prevent duplicate error if user re-submits their own competency
                DosenCompetency::firstOrCreate([
                    'dosen_id' => $request->dosen_id,
                    'prodi_competency_id' => $prodiCompetencyId,
                    'periode_akademik_id' => $activePeriod->id,
                ], [
                    'sk_kompetensi_id' => $activeSk->id,
                    'tanggal_mulai' => $activePeriod->tanggal_mulai,
                    'tanggal_selesai' => $activePeriod->tanggal_selesai,
                ]);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Data kompetensi dosen berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
