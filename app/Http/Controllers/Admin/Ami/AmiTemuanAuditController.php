<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiTemuanAudit;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiTemuanAuditController extends Controller
{
    public function indexSk(Request $request)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $query = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor'])
            ->withCount('temuanAudits');

        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('auditor_ketua_id', $user->id)
                  ->orWhereHas('auditorAnggotas', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  })
                  ->orWhereHas('anggotas', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            });
        }
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

        return view('admin.ami.temuan.list_sk', compact('skList', 'periodes', 'units'));
    }

    public function index($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $isKetua = $sk->isKetuaAuditor($user->id);
        $isAuditee = $sk->isAuditee($user->id);
        $isAuditor = $sk->isAuditor($user->id);

        if (!$isAdmin && !$isKetua && !$isAuditee && !$isAuditor) {
            abort(403);
        }

        $canEdit = $isAdmin || $isAuditor || $isKetua;
        return view('admin.ami.temuan.index', compact('sk', 'canEdit', 'isAdmin'));
    }

    public function data(Request $request, $skId)
    {
        $search = request('search.value');
        $data = AmiTemuanAudit::with('createdBy')->where('ami_sk_auditor_id', $skId);
        return DataTables::of($data)
            ->filter(function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->orWhere('deskripsi', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jenis_badge', function ($row) {
                $badges = [
                    'kesesuaian' => 'bg-success',
                    'observasi' => 'bg-info',
                    'ketidaksesuaian_minor' => 'bg-warning',
                    'ketidaksesuaian_mayor' => 'bg-danger',
                ];
                $badge = isset($badges[$row->jenis_temuan]) ? $badges[$row->jenis_temuan] : 'bg-secondary';
                return '<span class="badge '.$badge.'">'.ucfirst(str_replace('_',' ',$row->jenis_temuan)).'</span>';
            })
            ->addColumn('created_by_name', function ($r) {
                return $r->createdBy ? $r->createdBy->name : '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-inline-block">
                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0">
                        <li><button class="dropdown-item edit-record-button" data-id="'.$row->id.'" data-jenis_temuan="'.$row->jenis_temuan.'" data-deskripsi="'.e($row->deskripsi).'" data-rekomendasi="'.e($row->rekomendasi).'">Edit</button></li>
                        <div class="dropdown-divider"></div>
                        <li><form class="form-delete-record">'.method_field('DELETE').csrf_field().'<input type="hidden" name="id" value="'.$row->id.'"><input type="hidden" name="nama" value="Temuan #'.$row->id.'"><button type="submit" class="dropdown-item text-danger">Delete</button></form></li>
                    </ul></div>';
            })
            ->rawColumns(['action', 'jenis_badge'])
            ->toJson();
    }

    public function store(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'jenis_temuan' => 'required|in:kesesuaian,observasi,ketidaksesuaian_minor,ketidaksesuaian_mayor',
                'deskripsi' => 'required|string',
                'rekomendasi' => 'nullable|string',
            ]);
            AmiTemuanAudit::create([
                'ami_sk_auditor_id' => $skId,
                'jenis_temuan' => $request->jenis_temuan,
                'deskripsi' => $request->deskripsi,
                'rekomendasi' => $request->rekomendasi,
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Temuan berhasil ditambahkan'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function update(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $request->validate(['id'=>'required','jenis_temuan'=>'required','deskripsi'=>'required|string','rekomendasi'=>'nullable|string']);
            $temuan = AmiTemuanAudit::findOrFail($request->id);
            $temuan->update($request->only(['jenis_temuan','deskripsi','rekomendasi']));
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Temuan berhasil diupdate'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function delete(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            AmiTemuanAudit::findOrFail($request->id)->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Temuan berhasil dihapus'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }
}
