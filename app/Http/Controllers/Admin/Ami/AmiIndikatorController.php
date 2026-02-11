<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiIndikator;
use App\Models\AmiRubrikSkor;
use App\Models\AmiAuditTrail;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiIndikatorController extends Controller
{
    public function indexSk(Request $request)
    {
        $query = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor'])
            ->withCount('indikators');

        if ($request->filled('periode_id')) {
            $query->where('ami_periode_id', $request->periode_id);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nomor_asc':
                $query->orderBy('nomor_sk', 'asc');
                break;
            case 'nomor_desc':
                $query->orderBy('nomor_sk', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $skList = $query->paginate(9)->withQueryString();
        $periodes = AmiPeriode::orderBy('created_at', 'desc')->get();
        $units = AmiUnitAudit::orderBy('nama')->get();

        return view('admin.ami.indikator.list_sk', compact('skList', 'periodes', 'units'));
    }

    public function index(Request $request, $skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        
        $query = AmiIndikator::where('ami_sk_auditor_id', $skId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'LIKE', "%$search%")
                  ->orWhere('pertanyaan', 'LIKE', "%$search%")
                  ->orWhere('narasi_evaluasi_diri', 'LIKE', "%$search%");
            });
        }

        $sort = $request->get('sort', 'urutan_asc');
        switch ($sort) {
            case 'urutan_desc':
                $query->orderBy('urutan', 'desc');
                break;
            case 'kode_asc':
                $query->orderBy('kode', 'asc');
                break;
            case 'kode_desc':
                $query->orderBy('kode', 'desc');
                break;
            case 'urutan_asc':
            default:
                $query->orderBy('urutan', 'asc');
                break;
        }

        $indikators = $query->paginate(10)->withQueryString();

        return view('admin.ami.indikator.index', compact('sk', 'indikators'));
    }

    public function store(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'kode' => 'required|string|max:50',
                'pertanyaan' => 'required|string',
                'narasi_evaluasi_diri' => 'nullable|string',
                'urutan' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
                'rubrik_skor' => 'required|array',
                'rubrik_skor.*' => 'required|integer',
                'rubrik_deskripsi' => 'required|array',
                'rubrik_deskripsi.*' => 'required|string',
            ]);

            // Check unique kode per SK
            $exists = AmiIndikator::where('ami_sk_auditor_id', $skId)
                ->where('kode', $request->kode)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Kode indikator sudah ada di SK ini')->withInput();
            }

            $indikator = AmiIndikator::create([
                'ami_sk_auditor_id' => $skId,
                'kode' => $request->kode,
                'pertanyaan' => $request->pertanyaan,
                'narasi_evaluasi_diri' => $request->narasi_evaluasi_diri,
                'urutan' => $request->urutan,
                'is_active' => $request->is_active,
            ]);

            if ($request->rubrik_skor) {
                foreach ($request->rubrik_skor as $index => $skor) {
                    AmiRubrikSkor::create([
                        'ami_indikator_id' => $indikator->id,
                        'skor' => $skor,
                        'deskripsi' => $request->rubrik_deskripsi[$index],
                    ]);
                }
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'create_indikator',
                'model_type' => AmiIndikator::class,
                'model_id' => $indikator->id,
                'data_after' => $indikator->load('rubrikSkors')->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menambahkan Indikator');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function update(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'kode' => 'required|string|max:50',
                'pertanyaan' => 'required|string',
                'narasi_evaluasi_diri' => 'nullable|string',
                'urutan' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
                'rubrik_skor' => 'required|array',
                'rubrik_skor.*' => 'required|integer',
                'rubrik_deskripsi' => 'required|array',
                'rubrik_deskripsi.*' => 'required|string',
            ]);

            $indikator = AmiIndikator::where('ami_sk_auditor_id', $skId)->findOrFail($request->id);

            // Check unique kode per SK (excluding self)
            $exists = AmiIndikator::where('ami_sk_auditor_id', $skId)
                ->where('kode', $request->kode)
                ->where('id', '!=', $request->id)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Kode indikator sudah ada di SK ini')->withInput();
            }

            $before = $indikator->load('rubrikSkors')->toArray();
            $indikator->update($request->only(['kode', 'pertanyaan', 'narasi_evaluasi_diri', 'urutan', 'is_active']));

            // Delete existing rubriks and recreate
            $indikator->rubrikSkors()->delete();
            if ($request->rubrik_skor) {
                foreach ($request->rubrik_skor as $index => $skor) {
                    AmiRubrikSkor::create([
                        'ami_indikator_id' => $indikator->id,
                        'skor' => $skor,
                        'deskripsi' => $request->rubrik_deskripsi[$index],
                    ]);
                }
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'update_indikator',
                'model_type' => AmiIndikator::class,
                'model_id' => $indikator->id,
                'data_before' => $before,
                'data_after' => $indikator->fresh()->load('rubrikSkors')->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil mengupdate Indikator');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $data = AmiIndikator::where('ami_sk_auditor_id', $skId)->findOrFail($request->id);

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'delete_indikator',
                'model_type' => AmiIndikator::class,
                'model_id' => $data->id,
                'data_before' => $data->load('rubrikSkors')->toArray(),
                'ip_address' => $request->ip(),
            ]);

            $data->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil menghapus Indikator');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getRubrik($skId, $id)
    {
        $indikator = AmiIndikator::with('rubrikSkors')
            ->where('ami_sk_auditor_id', $skId)
            ->findOrFail($id);
        return response()->json($indikator);
    }
}
