<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiSkAuditorAnggota;
use App\Models\AmiPeriode;
use App\Models\AmiTargetAmi;
use App\Models\AmiAuditTrail;
use App\Models\AmiUnitAudit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\Ami\AmiModeSwitcherController;

class AmiSkAuditorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $periodes = AmiPeriode::orderBy('tahun_mulai', 'desc')->get();
        $units = AmiUnitAudit::orderBy('nama')->get();
        return view('admin.ami.sk-auditor.index', compact('periodes', 'units', 'isAdmin'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor', 'auditees.user'])
            ->select('ami_sk_auditors.*');

        if ($request->has('periode_id') && $request->periode_id && $request->periode_id != '*') {
            $data->where('ami_periode_id', $request->periode_id);
        }

        if ($request->has('unit_id') && $request->unit_id && $request->unit_id != '*') {
            $data->where('unit_id', $request->unit_id);
        }

        // Scope for unit users
        $user = Auth::user();
        if ($user->role === 'unit') {
            $currentMode = AmiModeSwitcherController::getCurrentMode();
            $data->where(function ($q) use ($user, $currentMode) {
                if ($currentMode === 'auditee') {
                    $q->whereHas('anggotas', function ($q2) use ($user) {
                        $q2->where('user_id', $user->id)->where('peran', 'auditee');
                    });
                } elseif ($currentMode === 'auditor') {
                    $q->where('auditor_ketua_id', $user->id)
                      ->orWhereHas('anggotas', function ($q2) use ($user) {
                          $q2->where('user_id', $user->id)->where('peran', 'auditor_anggota');
                      });
                } else {
                    // Fallback: show all SKs where user is assigned
                    $q->where('auditor_ketua_id', $user->id)
                      ->orWhereHas('anggotas', function ($q2) use ($user) {
                          $q2->where('user_id', $user->id);
                      });
                }
            });
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nomor_sk', 'LIKE', "%$search%");
                    $query->orWhereHas('unit', function ($q) use ($search) {
                        $q->where('nama', 'LIKE', "%$search%");
                    });
                });
            })
            ->addColumn('unit_nama', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('periode_nama', function ($row) {
                return $row->periode ? $row->periode->nama : '-';
            })
            ->addColumn('auditee_names', function ($row) {
            $names = $row->auditees->map(function ($a) {
                return $a->user ? $a->user->name : '-';
            })->filter();
            if ($names->isEmpty()) return '<span class="text-muted">-</span>';
            return $names->map(function ($n) {
                return '<span class="badge bg-label-info me-1 mb-1">' . e($n) . '</span>';
            })->implode('');
        })
        ->addColumn('ketua_nama', function ($row) {
            return $row->ketuaAuditor ? $row->ketuaAuditor->name : '-';
            })
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'draft' => 'bg-secondary',
                    'aktif' => 'bg-success',
                    'terkunci' => 'bg-warning',
                    'selesai' => 'bg-primary',
                ];
                $badgeClass = isset($badges[$row->status]) ? $badges[$row->status] : 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $isAdmin = in_array($user->role, ['admin', 'lpm']);

                if (!$isAdmin) {
                    // Unit users: detail only
                    return '
                        <a href="' . route('admin.ami.sk-auditor.show', $row->id) . '" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-eye me-1"></i>Detail
                        </a>';
                }

                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li><a class="dropdown-item" href="' . route('admin.ami.sk-auditor.show', $row->id) . '"><i class="ti ti-eye me-2"></i>Detail</a></li>
                            <li><a class="dropdown-item" href="' . route('admin.ami.sk-auditor.edit', $row->id) . '"><i class="ti ti-pencil me-2"></i>Edit</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->nomor_sk . '">
                                    <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'status_badge', 'auditee_names'])
            ->toJson();
    }

    public function create()
    {
        $periodes = AmiPeriode::where('status', '!=', 'selesai')->orderBy('tahun_mulai', 'desc')->get();
        $units = AmiUnitAudit::orderBy('nama')->get();
        $users = User::orderBy('name')->get();
        return view('admin.ami.sk-auditor.create', compact('periodes', 'units', 'users'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'ami_periode_id' => 'required|exists:ami_periodes,id',
                'unit_id' => 'required|exists:ami_unit_audits,id',
                'nomor_sk' => 'required|string|max:255',
                'tanggal_sk' => 'required|date',
                'auditor_ketua_id' => 'required|exists:users,id',
                'status' => 'required|in:draft,aktif,terkunci,selesai',
                'catatan' => 'nullable|string',
                'auditor_anggota' => 'nullable|array',
                'auditor_anggota.*' => 'exists:users,id',
                'auditee' => 'nullable|array',
                'auditee.*' => 'exists:users,id',
            ]);

            $sk = AmiSkAuditor::create($request->only([
                'ami_periode_id', 'unit_id', 'nomor_sk', 'tanggal_sk',
                'auditor_ketua_id', 'status', 'catatan'
            ]));

            if ($request->has('auditor_anggota')) {
                foreach ($request->auditor_anggota as $userId) {
                    AmiSkAuditorAnggota::create([
                        'ami_sk_auditor_id' => $sk->id,
                        'user_id' => $userId,
                        'peran' => 'auditor_anggota',
                    ]);
                }
            }

            if ($request->has('auditee')) {
                foreach ($request->auditee as $userId) {
                    AmiSkAuditorAnggota::create([
                        'ami_sk_auditor_id' => $sk->id,
                        'user_id' => $userId,
                        'peran' => 'auditee',
                    ]);
                }
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'create_sk_auditor',
                'model_type' => AmiSkAuditor::class,
                'model_id' => $sk->id,
                'data_after' => $sk->load('anggotas')->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('admin.ami.sk-auditor.index')
                ->with('success', 'SK Auditor berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $sk = AmiSkAuditor::with([
            'periode', 'unit', 'ketuaAuditor',
            'anggotas.user', 'evaluasiDiris', 'asesmens', 'temuanAudits', 'targetAmis'
        ])->findOrFail($id);

        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $isKetua = $sk->isKetuaAuditor($user->id);
        $isAuditor = $sk->isAuditor($user->id);
        $isAuditee = $sk->isAuditee($user->id);

        return view('admin.ami.sk-auditor.show', compact('sk', 'isAdmin', 'isKetua', 'isAuditor', 'isAuditee'));
    }

    public function edit($id)
    {
        $sk = AmiSkAuditor::with('anggotas')->findOrFail($id);
        $periodes = AmiPeriode::orderBy('tahun_mulai', 'desc')->get();
        $units = AmiUnitAudit::orderBy('nama')->get();
        $users = User::orderBy('name')->get();
        return view('admin.ami.sk-auditor.edit', compact('sk', 'periodes', 'units', 'users'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'ami_periode_id' => 'required|exists:ami_periodes,id',
                'unit_id' => 'required|exists:ami_unit_audits,id',
                'nomor_sk' => 'required|string|max:255',
                'tanggal_sk' => 'required|date',
                'auditor_ketua_id' => 'required|exists:users,id',
                'status' => 'required|in:draft,aktif,terkunci,selesai',
                'catatan' => 'nullable|string',
                'auditor_anggota' => 'nullable|array',
                'auditor_anggota.*' => 'exists:users,id',
                'auditee' => 'nullable|array',
                'auditee.*' => 'exists:users,id',
            ]);

            $sk = AmiSkAuditor::findOrFail($id);
            $before = $sk->load('anggotas')->toArray();

            $sk->update($request->only([
                'ami_periode_id', 'unit_id', 'nomor_sk', 'tanggal_sk',
                'auditor_ketua_id', 'status', 'catatan'
            ]));

            $sk->anggotas()->delete();

            if ($request->has('auditor_anggota')) {
                foreach ($request->auditor_anggota as $userId) {
                    AmiSkAuditorAnggota::create([
                        'ami_sk_auditor_id' => $sk->id,
                        'user_id' => $userId,
                        'peran' => 'auditor_anggota',
                    ]);
                }
            }

            if ($request->has('auditee')) {
                foreach ($request->auditee as $userId) {
                    AmiSkAuditorAnggota::create([
                        'ami_sk_auditor_id' => $sk->id,
                        'user_id' => $userId,
                        'peran' => 'auditee',
                    ]);
                }
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'update_sk_auditor',
                'model_type' => AmiSkAuditor::class,
                'model_id' => $sk->id,
                'data_before' => $before,
                'data_after' => $sk->fresh()->load('anggotas')->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('admin.ami.sk-auditor.index')
                ->with('success', 'SK Auditor berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $sk = AmiSkAuditor::findOrFail($request->id);

            if ($sk->status !== 'draft') {
                return ['status' => false, 'type' => 'error', 'message' => 'Hanya SK berstatus draft yang dapat dihapus'];
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'delete_sk_auditor',
                'model_type' => AmiSkAuditor::class,
                'model_id' => $sk->id,
                'data_before' => $sk->toArray(),
                'ip_address' => $request->ip(),
            ]);

            $sk->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'SK Auditor berhasil dihapus'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }
}
