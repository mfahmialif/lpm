<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiTargetAmi;
use App\Models\AmiSkAuditor;
use App\Models\AmiAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\Ami\AmiModeSwitcherController;

use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;

class AmiTargetAmiController extends Controller
{
    public function index(Request $request)
    {
        $query = AmiTargetAmi::with(['createdBy', 'skAuditor.unit', 'skAuditor.periode']);

        // Filter by specific SK Auditor (from details page)
        if ($request->has('ami_sk_auditor_id')) {
            $query->where('ami_sk_auditor_id', $request->ami_sk_auditor_id);
        }

        // Filter by Periode (via relation)
        if ($request->filled('periode_id')) {
            $query->whereHas('skAuditor', function($q) use ($request) {
                $q->where('ami_periode_id', $request->periode_id);
            });
        }

        // Filter by Unit (via relation)
        if ($request->filled('unit_id')) {
            $query->whereHas('skAuditor', function($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nomor_asc':
                 // Sort by related SK number? Note: This might be complex if SK is null
                 // For Target AMI, maybe sort by 'kode_target'
                $query->orderBy('kode_target', 'asc');
                break;
            case 'nomor_desc':
                $query->orderBy('kode_target', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $targets = $query->paginate(9)->withQueryString();
        $periodes = AmiPeriode::orderBy('created_at', 'desc')->get();
        $units = AmiUnitAudit::orderBy('nama')->get();
        
        $sk = null;
    if ($request->has('ami_sk_auditor_id')) {
        $sk = AmiSkAuditor::find($request->ami_sk_auditor_id);
    }

    $user = Auth::user();
    $isAdmin = in_array($user->role, ['admin', 'lpm']) || ($user->role === 'unit' && AmiModeSwitcherController::getCurrentMode() === 'auditor');

    return view('admin.ami.target-ami.index', compact('targets', 'sk', 'periodes', 'units', 'isAdmin'));
}



    public function create(Request $request)
    {
        $skAuditors = AmiSkAuditor::where('status', '!=', 'selesai')->orderBy('created_at', 'desc')->get();
        $selectedSkId = $request->ami_sk_auditor_id;
        return view('admin.ami.target-ami.form', compact('skAuditors', 'selectedSkId'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'ami_sk_auditor_id' => 'required|exists:ami_sk_auditors,id',
                'kode_target' => 'required|string|max:50|unique:ami_target_amis,kode_target',
                'tahun' => 'required|digits:4',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'standar_acuan' => 'nullable|string',
                'ruang_lingkup' => 'nullable|string',
            ]);

            $data = AmiTargetAmi::create(array_merge(
                $request->only(['ami_sk_auditor_id', 'kode_target', 'tahun', 'tanggal_mulai', 'tanggal_selesai', 'standar_acuan', 'ruang_lingkup']),
                ['created_by' => Auth::id(), 'status' => 'draft']
            ));

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'create_target_ami',
                'model_type' => AmiTargetAmi::class,
                'model_id' => $data->id,
                'data_after' => $data->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('admin.ami.target-ami.show', $data->id)
                ->with('success', 'Target AMI berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $target = AmiTargetAmi::with([
            'createdBy',
            'skAuditor.unit',
            'skAuditor.periode',
            'skAuditor.ketuaAuditor',
        ])->findOrFail($id);

        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']) || ($user->role === 'unit' && AmiModeSwitcherController::getCurrentMode() === 'auditor');

        return view('admin.ami.target-ami.show', compact('target', 'isAdmin'));
    }

    public function edit($id)
    {
        $target = AmiTargetAmi::findOrFail($id);

        if ($target->status !== 'draft') {
            return redirect()->route('admin.ami.target-ami.show', $id)
                ->with('error', 'Hanya Target AMI berstatus draft yang dapat diedit');
        }

        $skAuditors = AmiSkAuditor::orderBy('created_at', 'desc')->get();
        return view('admin.ami.target-ami.form', compact('target', 'skAuditors'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $target = AmiTargetAmi::findOrFail($id);

            if ($target->status !== 'draft') {
                return back()->with('error', 'Hanya Target AMI berstatus draft yang dapat diedit');
            }

            $request->validate([
                'ami_sk_auditor_id' => 'required|exists:ami_sk_auditors,id',
                'kode_target' => 'required|string|max:50|unique:ami_target_amis,kode_target,' . $id,
                'tahun' => 'required|digits:4',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'standar_acuan' => 'nullable|string',
                'ruang_lingkup' => 'nullable|string',
            ]);

            $before = $target->toArray();
            $target->update($request->only(['ami_sk_auditor_id', 'kode_target', 'tahun', 'tanggal_mulai', 'tanggal_selesai', 'standar_acuan', 'ruang_lingkup']));

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'update_target_ami',
                'model_type' => AmiTargetAmi::class,
                'model_id' => $target->id,
                'data_before' => $before,
                'data_after' => $target->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return redirect()->route('admin.ami.target-ami.show', $id)
                ->with('success', 'Target AMI berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            $target = AmiTargetAmi::findOrFail($request->id);

            if (!$target->canBeDeleted()) {
                return ['status' => false, 'type' => 'error', 'message' => 'Target AMI tidak dapat dihapus karena sudah memiliki SK Auditor'];
            }

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'delete_target_ami',
                'model_type' => AmiTargetAmi::class,
                'model_id' => $target->id,
                'data_before' => $target->toArray(),
                'ip_address' => $request->ip(),
            ]);

            $target->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Target AMI berhasil dihapus'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $target = AmiTargetAmi::with('skAuditor')->findOrFail($id);
            $before = $target->toArray();
            $newStatus = null;

            if ($target->status === 'draft') {
                if (!$target->canBeAktifkan()) {
                    return back()->with('error', 'Target AMI tidak dapat diaktifkan. Pastikan tanggal mulai dan selesai sudah valid.');
                }
                $newStatus = 'aktif';
            } elseif ($target->status === 'aktif') {
                if (!$target->canBeDitutup()) {
                    return back()->with('error', 'Target AMI tidak dapat ditutup.');
                }
                $newStatus = 'ditutup';
            } else {
                return back()->with('error', 'Status Target AMI tidak dapat diubah lagi.');
            }

            $target->update(['status' => $newStatus]);

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'change_status_target_ami',
                'model_type' => AmiTargetAmi::class,
                'model_id' => $target->id,
                'data_before' => $before,
                'data_after' => $target->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            $statusLabels = ['aktif' => 'diaktifkan', 'ditutup' => 'ditutup'];
            return back()->with('success', 'Target AMI berhasil ' . ($statusLabels[$newStatus] ?? 'diperbarui'));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }
    }
}
