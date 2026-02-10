<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiIndikator;
use App\Models\AmiRubrikSkor;
use App\Models\AmiAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiIndikatorController extends Controller
{
    public function index($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        return view('admin.ami.indikator.index', compact('sk'));
    }

    public function data(Request $request, $skId)
    {
        $search = request('search.value');
        $data = AmiIndikator::with('rubrikSkors')
            ->where('ami_sk_auditor_id', $skId)
            ->select('ami_indikators.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('kode', 'LIKE', "%$search%");
                    $query->orWhere('pertanyaan', 'LIKE', "%$search%");
                });
            })
            ->addColumn('status_badge', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Nonaktif</span>';
            })
            ->addColumn('rubrik_count', function ($row) {
                return $row->rubrikSkors->count() . '/4';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li><button class="dropdown-item btn-detail" data-id="' . $row->id . '">Detail Rubrik</button></li>
                            <li>
                                <button class="dropdown-item edit-record-button"
                                    data-id="' . $row->id . '"
                                    data-kode="' . $row->kode . '"
                                    data-pertanyaan="' . e($row->pertanyaan) . '"
                                    data-narasi_evaluasi_diri="' . e($row->narasi_evaluasi_diri) . '"
                                    data-urutan="' . $row->urutan . '"
                                    data-is_active="' . ($row->is_active ? '1' : '0') . '"
                                    data-rubrik=\'' . $row->rubrikSkors->toJson() . '\'
                                    >Edit</button></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->kode . '">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'status_badge'])
            ->toJson();
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
                return ['status' => false, 'type' => 'error', 'message' => 'Kode indikator sudah ada di SK ini'];
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
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil menambahkan Indikator'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
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
                return ['status' => false, 'type' => 'error', 'message' => 'Kode indikator sudah ada di SK ini'];
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
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil mengupdate Indikator'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
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
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil menghapus Indikator'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
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
