<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiIndikator;
use App\Models\AmiEvaluasiDiri;
use App\Models\AmiEvaluasiDiriFile;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AmiEvaluasiDiriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $query = AmiSkAuditor::with(['periode', 'unit', 'evaluasiDiris']);

        if ($isAdmin) {
            $query->whereIn('status', ['aktif', 'terkunci', 'selesai']);
        } else {
            $query->whereIn('status', ['aktif', 'terkunci', 'selesai'])
                ->where(function ($q) use ($user) {
                    $q->whereHas('anggotas', function ($q2) use ($user) {
                        $q2->where('user_id', $user->id);
                    })
                    ->orWhere('auditor_ketua_id', $user->id);
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

        // For unit users, only show units from their assigned SKs
        if ($isAdmin) {
            $units = AmiUnitAudit::orderBy('nama')->get();
        } else {
            $userSkUnitIds = AmiSkAuditor::where(function ($q) use ($user) {
                $q->whereHas('anggotas', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                })
                ->orWhere('auditor_ketua_id', $user->id);
            })->pluck('unit_id')->unique();
            $units = AmiUnitAudit::whereIn('id', $userSkUnitIds)->orderBy('nama')->get();
        }

        return view('admin.ami.evaluasi-diri.index', compact('skList', 'isAdmin', 'periodes', 'units'));
    }

    public function show(Request $request, $skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        if (!$isAdmin && !$sk->isAuditee($user->id) && !$sk->isAuditor($user->id)) {
            abort(403, 'Anda tidak memiliki akses ke evaluasi diri ini');
        }

        $indikators = AmiIndikator::where('ami_sk_auditor_id', $sk->id)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $evaluasiDiris = AmiEvaluasiDiri::with('files')
            ->where('ami_sk_auditor_id', $sk->id)
            ->get()
            ->keyBy('ami_indikator_id');

        // Prepare data for view
        $existingAnswers = $evaluasiDiris;
        $existingFiles = [];
        foreach ($evaluasiDiris as $indId => $eval) {
            $existingFiles[$indId] = $eval->files;
        }

        $currentMode = AmiModeSwitcherController::getCurrentMode();

        $canEdit = ($isAdmin || ($sk->status === 'aktif' && $sk->isAuditee($user->id) && $currentMode === 'auditee')) && !$request->has('readonly');

        return view('admin.ami.evaluasi-diri.show', compact(
            'sk', 'indikators', 'existingAnswers', 'existingFiles', 'canEdit', 'isAdmin', 'currentMode'
        ));
    }

    public function saveJawaban(Request $request)
    {
        try {
            $request->validate([
                'ami_sk_auditor_id' => 'required|exists:ami_sk_auditors,id',
                'ami_indikator_id' => 'required|exists:ami_indikators,id',
                'jawaban' => 'nullable|string',
            ]);

            $sk = AmiSkAuditor::findOrFail($request->ami_sk_auditor_id);
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);

            if (!$isAdmin && (!$sk->isAuditee($user->id) || $sk->status !== 'aktif' || AmiModeSwitcherController::getCurrentMode() !== 'auditee')) {
                return response()->json(['status' => false, 'message' => 'Tidak memiliki akses'], 403);
            }

            $evaluasi = AmiEvaluasiDiri::updateOrCreate(
                [
                    'ami_sk_auditor_id' => $request->ami_sk_auditor_id,
                    'ami_indikator_id' => $request->ami_indikator_id,
                ],
                [
                    'jawaban' => $request->jawaban,
                    'submitted_by' => $user->id,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Jawaban tersimpan',
                'eval_status' => $evaluasi->status,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'ami_sk_auditor_id' => 'required|exists:ami_sk_auditors,id',
                'ami_indikator_id' => 'required|exists:ami_indikators,id',
                'files' => 'required|array',
                'files.*' => 'file|max:10240',
            ]);

            $sk = AmiSkAuditor::findOrFail($request->ami_sk_auditor_id);
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);

            if (!$isAdmin && (!$sk->isAuditee($user->id) || $sk->status !== 'aktif' || AmiModeSwitcherController::getCurrentMode() !== 'auditee')) {
                return response()->json(['status' => false, 'message' => 'Tidak memiliki akses'], 403);
            }

            $evaluasi = AmiEvaluasiDiri::firstOrCreate(
                [
                    'ami_sk_auditor_id' => $request->ami_sk_auditor_id,
                    'ami_indikator_id' => $request->ami_indikator_id,
                ],
                ['submitted_by' => $user->id]
            );

            $relativePath = 'ami/evaluasi-diri/' . $sk->id;
            $destinationPath = public_path($relativePath);
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $savedFiles = [];
            foreach ($request->file('files') as $file) {
                // Sanitize filename
                $originalName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $file->getClientOriginalName());
                $fileName = time() . '_' . $originalName; 
                
                $file->move($destinationPath, $fileName);

                $savedFiles[] = AmiEvaluasiDiriFile::create([
                    'ami_evaluasi_diri_id' => $evaluasi->id,
                    'file_path' => $relativePath . '/' . $fileName,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'File berhasil diunggah',
                'files' => $savedFiles,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $file = AmiEvaluasiDiriFile::findOrFail($request->file_id);
            $evaluasi = $file->evaluasiDiri;
            $sk = AmiSkAuditor::findOrFail($evaluasi->ami_sk_auditor_id);

            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);

            if (!$isAdmin && (!$sk->isAuditee($user->id) || $sk->status !== 'aktif' || AmiModeSwitcherController::getCurrentMode() !== 'auditee')) {
                return response()->json(['status' => false, 'message' => 'Tidak memiliki akses'], 403);
            }

            $fullPath = public_path($file->file_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            $file->delete();

            return response()->json(['status' => true, 'message' => 'File berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function getProgress($skId)
    {
        $sk = AmiSkAuditor::findOrFail($skId);
        $indikators = AmiIndikator::where('ami_sk_auditor_id', $sk->id)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $evaluasiDiris = AmiEvaluasiDiri::with('files')
            ->where('ami_sk_auditor_id', $sk->id)
            ->get()
            ->keyBy('ami_indikator_id');

        $total = $indikators->count();
        $answered = 0;
        $withFiles = 0;
        $statuses = [];

        foreach ($indikators as $ind) {
            $eval = isset($evaluasiDiris[$ind->id]) ? $evaluasiDiris[$ind->id] : null;
            if ($eval) {
                if ($eval->jawaban) $answered++;
                if ($eval->files->count() > 0) $withFiles++;
                $statuses[$ind->id] = $eval->status;
            } else {
                $statuses[$ind->id] = 'merah';
            }
        }

        return response()->json([
            'total' => $total,
            'answered' => $answered,
            'with_files' => $withFiles,
            'statuses' => $statuses,
        ]);
    }
}
