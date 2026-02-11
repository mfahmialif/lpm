<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiHasilTemuan;
use App\Models\AmiHasilTemuanDetail;
use App\Models\AmiSkAuditor;
use App\Models\AmiTemuanAudit;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class AmiHasilTemuanController extends Controller
{
    public function indexSk(Request $request)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $query = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor'])
            ->withCount('hasilTemuans');

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

        return view('admin.ami.hasil-temuan.list_sk', compact('skList', 'periodes', 'units'));
    }

    private function checkAccess($sk)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $isKetua = $sk->isKetuaAuditor($user->id);
        $isAuditor = $sk->isAuditor($user->id);
        $isAuditee = $sk->isAuditee($user->id);

        if (!$isAdmin && !$isAuditor && !$isAuditee) {
            abort(403);
        }

        $canEdit = $isAdmin || $isAuditor; // admin/lpm and auditors can edit
        $canDelete = in_array($user->role, ['admin']) || $isAuditor; // admin and auditors can delete

        return compact('isAdmin', 'isKetua', 'isAuditor', 'isAuditee', 'canEdit', 'canDelete');
    }

    public function index($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $access = $this->checkAccess($sk);

        return view('admin.ami.hasil-temuan.index', array_merge(
            compact('sk'),
            $access
        ));
    }

    public function data(Request $request, $skId)
    {
        $search = request('search.value');
        $data = AmiHasilTemuan::with(['createdBy', 'temuanAudits'])
            ->where('ami_sk_auditor_id', $skId);

        return DataTables::of($data)
            ->filter(function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->orWhere('judul', 'LIKE', "%$search%")
                       ->orWhere('ringkasan', 'LIKE', "%$search%");
                });
            })
            ->addColumn('kategori_badge', function ($row) {
                $badges = [
                    'kesesuaian' => 'bg-success',
                    'observasi' => 'bg-info',
                    'ketidaksesuaian_minor' => 'bg-warning',
                    'ketidaksesuaian_mayor' => 'bg-danger',
                ];
                $badge = isset($badges[$row->kategori]) ? $badges[$row->kategori] : 'bg-secondary';
                return '<span class="badge '.$badge.'">'.ucfirst(str_replace('_', ' ', $row->kategori)).'</span>';
            })
            ->addColumn('temuan_count', function ($row) {
                $count = $row->temuanAudits->count();
                return '<span class="badge bg-label-primary">'.$count.' temuan</span>';
            })
            ->addColumn('ringkasan_short', function ($row) {
                return $row->ringkasan && strlen($row->ringkasan) > 80
                    ? substr($row->ringkasan, 0, 80) . '...'
                    : $row->ringkasan;
            })
            ->addColumn('created_by_name', function ($r) {
                return $r->createdBy ? $r->createdBy->name : '-';
            })
            ->addColumn('action', function ($row) use ($skId) {
                $user = Auth::user();
                $isAdmin = in_array($user->role, ['admin', 'lpm']);
                $isDeleteAdmin = in_array($user->role, ['admin']);

                $showBtn = '<li><a class="dropdown-item" href="' . route('admin.ami.hasil-temuan.show', [$skId, $row->id]) . '"><i class="ti ti-eye me-2"></i>Detail</a></li>';
                $editBtn = $isAdmin ? '<li><a class="dropdown-item" href="' . route('admin.ami.hasil-temuan.edit', [$skId, $row->id]) . '"><i class="ti ti-pencil me-2"></i>Edit</a></li>' : '';
                $divider = $isDeleteAdmin ? '<div class="dropdown-divider"></div>' : '';
                $deleteBtn = $isDeleteAdmin ? '<li><form class="form-delete-record">'.method_field('DELETE').csrf_field().'<input type="hidden" name="id" value="'.$row->id.'"><input type="hidden" name="nama" value="'.e($row->judul).'"><button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Delete</button></form></li>' : '';

                return '<div class="d-inline-block">
                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0">
                        '.$showBtn.'
                        '.$editBtn.'
                        '.$divider.'
                        '.$deleteBtn.'
                    </ul></div>';
            })
            ->rawColumns(['action', 'kategori_badge', 'temuan_count'])
            ->toJson();
    }

    public function create($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'lpm'])) {
            abort(403);
        }
        $temuanAudits = AmiTemuanAudit::where('ami_sk_auditor_id', $skId)->orderBy('id')->get();
        return view('admin.ami.hasil-temuan.create', compact('sk', 'temuanAudits'));
    }

    public function store(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'lpm'])) {
                abort(403);
            }

            $request->validate([
                'judul' => 'required|string|max:255',
                'ringkasan' => 'required|string',
                'kategori' => 'required|in:kesesuaian,observasi,ketidaksesuaian_minor,ketidaksesuaian_mayor',
                'temuan_audit_ids' => 'required|array|min:1',
                'temuan_audit_ids.*' => 'exists:ami_temuan_audits,id',
            ]);

            $hasil = AmiHasilTemuan::create([
                'ami_sk_auditor_id' => $skId,
                'judul' => $request->judul,
                'ringkasan' => $request->ringkasan,
                'kategori' => $request->kategori,
                'created_by' => Auth::id(),
            ]);

            $hasil->temuanAudits()->sync($request->temuan_audit_ids);

            DB::commit();
            return redirect()->route('admin.ami.hasil-temuan.index', $skId)
                ->with('success', 'Hasil Temuan berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit($skId, $id)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'lpm'])) {
            abort(403);
        }
        $hasil = AmiHasilTemuan::with('temuanAudits')->findOrFail($id);
        $temuanAudits = AmiTemuanAudit::where('ami_sk_auditor_id', $skId)->orderBy('id')->get();
        $selectedTemuanIds = $hasil->temuanAudits->pluck('id')->toArray();
        return view('admin.ami.hasil-temuan.edit', compact('sk', 'hasil', 'temuanAudits', 'selectedTemuanIds'));
    }

    public function update(Request $request, $skId, $id)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'lpm'])) {
                abort(403);
            }

            $request->validate([
                'judul' => 'required|string|max:255',
                'ringkasan' => 'required|string',
                'kategori' => 'required|in:kesesuaian,observasi,ketidaksesuaian_minor,ketidaksesuaian_mayor',
                'temuan_audit_ids' => 'required|array|min:1',
                'temuan_audit_ids.*' => 'exists:ami_temuan_audits,id',
            ]);

            $hasil = AmiHasilTemuan::findOrFail($id);
            $hasil->update($request->only(['judul', 'ringkasan', 'kategori']));
            $hasil->temuanAudits()->sync($request->temuan_audit_ids);

            DB::commit();
            return redirect()->route('admin.ami.hasil-temuan.index', $skId)
                ->with('success', 'Hasil Temuan berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if (!in_array($user->role, ['admin'])) {
                return ['status' => false, 'type' => 'error', 'message' => 'Hanya admin yang dapat menghapus'];
            }

            AmiHasilTemuan::findOrFail($request->id)->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Hasil Temuan berhasil dihapus'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function show($skId, $id)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $access = $this->checkAccess($sk);
        $hasil = AmiHasilTemuan::with(['createdBy', 'temuanAudits.createdBy'])->findOrFail($id);

        return view('admin.ami.hasil-temuan.show', array_merge(
            compact('sk', 'hasil'),
            $access
        ));
    }
}
