<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dosen;
use App\Models\Prodi;
use App\Models\ProdiCompetency;
use App\Models\DosenCompetency;
use App\Models\PeriodeAkademik;
use App\Models\SkKompetensi;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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

        // Validasi: prodi_id wajib
        if (!$prodiId) {
            return response()->json([
                'data' => [],
                'message' => 'Pilih program studi terlebih dahulu.',
                'dosen_has_competency' => false
            ]);
        }

        // Get Active Period
        $activePeriod = PeriodeAkademik::where('is_active', true)->first();

        if (!$activePeriod) {
            return response()->json([
                'data' => [],
                'message' => 'Tidak ada periode akademik aktif.',
                'dosen_has_competency' => false
            ]);
        }

        // Cek apakah dosen sudah memiliki kompetensi di periode aktif (di prodi manapun)
        if ($dosenId) {
            $existingCompetency = DosenCompetency::where('dosen_id', $dosenId)
                ->where('periode_akademik_id', $activePeriod->id)
                ->first();

            if ($existingCompetency) {
                return response()->json([
                    'data' => [],
                    'message' => 'Dosen ini sudah memiliki kompetensi pada periode ini.',
                    'dosen_has_competency' => true
                ]);
            }
        }

        // Get IDs of competencies that are already assigned (by any dosen)
        $assignedCompetencyIds = DosenCompetency::where('periode_akademik_id', $activePeriod->id)
            ->join('prodi_competencies', 'dosen_competencies.prodi_competency_id', '=', 'prodi_competencies.id')
            ->pluck('prodi_competencies.competency_id')
            ->toArray();

        $query = ProdiCompetency::where('prodi_id', $prodiId);

        if (!empty($assignedCompetencyIds)) {
            $query->whereNotIn('competency_id', $assignedCompetencyIds);
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

        return response()->json([
            'data' => $competencies,
            'message' => $competencies->isEmpty() ? 'Tidak ada kompetensi tersedia untuk program studi ini.' : null,
            'dosen_has_competency' => false
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required',
            'prodi_id' => 'required',
            'competency_ids' => 'required|array|max:1', // Hanya boleh pilih 1 kompetensi
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

            // Validasi: Cek apakah dosen sudah memiliki kompetensi di periode aktif (di prodi manapun)
            $existingCompetency = DosenCompetency::where('dosen_id', $request->dosen_id)
                ->where('periode_akademik_id', $activePeriod->id)
                ->first();

            if ($existingCompetency) {
                return response()->json([
                    'status' => false,
                    'message' => 'Dosen ini sudah memiliki kompetensi pada periode ini. 1 dosen hanya boleh memilih 1 kompetensi.'
                ], 400);
            }

            foreach ($request->competency_ids as $prodiCompetencyId) {
                DosenCompetency::create([
                    'dosen_id' => $request->dosen_id,
                    'prodi_competency_id' => $prodiCompetencyId,
                    'periode_akademik_id' => $activePeriod->id,
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

    public function getData(Request $request)
    {
        $query = DosenCompetency::select([
            'dosen_competencies.id',
            'dosen_competencies.dosen_id',
            'dosen_competencies.prodi_competency_id',
            'dosen_competencies.periode_akademik_id',
            'dosen_competencies.sk_kompetensi_id',
            'dosen_competencies.tanggal_mulai',
            'dosen_competencies.tanggal_selesai',
            'mst_dosen.nama as nama_dosen',
            'mst_dosen.nidn as nidn_dosen',
            'prodis.nama as nama_prodi',
            'competencies.nama as nama_kompetensi',
            'periode_akademik.nama_periode as nama_periode',
        ])
            ->leftJoin('mst_dosen', 'dosen_competencies.dosen_id', '=', 'mst_dosen.id')
            ->leftJoin('prodi_competencies', 'dosen_competencies.prodi_competency_id', '=', 'prodi_competencies.id')
            ->leftJoin('prodis', 'prodi_competencies.prodi_id', '=', 'prodis.id')
            ->leftJoin('competencies', 'prodi_competencies.competency_id', '=', 'competencies.id')
            ->leftJoin('periode_akademik', 'dosen_competencies.periode_akademik_id', '=', 'periode_akademik.id');

        // Filter hanya untuk periode akademik yang aktif
        $activePeriod = PeriodeAkademik::where('is_active', true)->first();
        if ($activePeriod) {
            $query->where('dosen_competencies.periode_akademik_id', $activePeriod->id);
        }

        // Filter by prodi if provided
        if ($request->has('prodi_id') && $request->prodi_id) {
            $query->where('prodi_competencies.prodi_id', $request->prodi_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($row) {
                $mulai = $row->tanggal_mulai ? date('d/m/Y', strtotime($row->tanggal_mulai)) : '-';
                $selesai = $row->tanggal_selesai ? date('d/m/Y', strtotime($row->tanggal_selesai)) : '-';
                return $mulai . ' - ' . $selesai;
            })
            ->make(true);
    }

    public function destroy($id)
    {
        try {
            $dosenCompetency = DosenCompetency::findOrFail($id);
            $dosenCompetency->delete();

            return response()->json(['status' => true, 'message' => 'Data kompetensi dosen berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
