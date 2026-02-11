<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiIndikator;
use App\Models\AmiAsesmen;
use App\Models\AmiEvaluasiDiri;
use App\Models\AmiAuditTrail;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AmiAsesmenController extends Controller
{
    /**
     * List SK yang bisa di-ases
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $query = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor', 'asesmens']);

        if ($isAdmin) {
            $query->whereIn('status', ['aktif', 'terkunci', 'selesai']);
        } else {
            $query->whereIn('status', ['aktif', 'terkunci'])
                ->where(function ($q) use ($user) {
                    $q->where('auditor_ketua_id', $user->id)
                      ->orWhereHas('auditorAnggotas', function ($q2) use ($user) {
                          $q2->where('user_id', $user->id);
                      })
                      ->orWhereHas('anggotas', function ($q2) use ($user) {
                          $q2->where('user_id', $user->id)->where('peran', 'auditee');
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

        // For unit users, only show units from their assigned SKs (as auditor)
        if ($isAdmin) {
            $units = AmiUnitAudit::orderBy('nama')->get();
        } else {
            $userSkUnitIds = AmiSkAuditor::where(function ($q) use ($user) {
                $q->where('auditor_ketua_id', $user->id)
                  ->orWhereHas('auditorAnggotas', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })->pluck('unit_id')->unique();
            $units = AmiUnitAudit::whereIn('id', $userSkUnitIds)->orderBy('nama')->get();
        }

        return view('admin.ami.asesmen.index', compact('skList', 'isAdmin', 'periodes', 'units'));
    }

    /**
     * Interface asesmen per SK
     */
    public function show(Request $request, $skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor'])->findOrFail($skId);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $isKetua = $sk->isKetuaAuditor($user->id);
        $isAuditor = $sk->isAuditor($user->id);
        $isAuditee = $sk->isAuditee($user->id);

        if (!$isAdmin && !$isAuditor && !$isAuditee) {
            abort(403, 'Anda tidak memiliki akses ke asesmen ini');
        }

        // Ambil indikator sesuai SK
        $indikators = AmiIndikator::with('rubrikSkors')
            ->where('ami_sk_auditor_id', $sk->id)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        // Jawaban evaluasi diri (read-only untuk auditor)
        $evaluasiDiris = AmiEvaluasiDiri::with('files')
            ->where('ami_sk_auditor_id', $sk->id)
            ->get()
            ->keyBy('ami_indikator_id');

        // Asesmen yang sudah ada
        $existingScores = AmiAsesmen::where('ami_sk_auditor_id', $sk->id)
            ->get()
            ->keyBy('ami_indikator_id');

        // Auditee can only view in read-only mode
        $canEdit = ($isAdmin || ($isAuditor && in_array($sk->status, ['aktif']))) && !$request->has('readonly');
        $canFinalize = ($isAdmin || ($isKetua && $sk->status === 'aktif')) && !$request->has('readonly');

        return view('admin.ami.asesmen.show', compact(
            'sk', 'indikators', 'evaluasiDiris', 'existingScores',
            'canEdit', 'canFinalize', 'isAdmin', 'isKetua'
        ));
    }

    /**
     * AJAX save skor per indikator
     */
    public function saveSkor(Request $request)
    {
        try {
            $request->validate([
                'ami_sk_auditor_id' => 'required|exists:ami_sk_auditors,id',
                'ami_indikator_id' => 'required|exists:ami_indikators,id',
                'skor_pilihan' => 'required|array',
                'skor_pilihan.*' => 'integer|min:1',
                'catatan_asesor' => 'nullable|string',
            ]);

            $sk = AmiSkAuditor::findOrFail($request->ami_sk_auditor_id);
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);

            if (!$isAdmin && (!$sk->isAuditor($user->id) || $sk->status !== 'aktif')) {
                return response()->json(['status' => false, 'message' => 'Tidak memiliki akses'], 403);
            }

            // Cek apakah sudah difinalisasi
            $existing = AmiAsesmen::where('ami_sk_auditor_id', $request->ami_sk_auditor_id)
                ->where('ami_indikator_id', $request->ami_indikator_id)
                ->first();

            if ($existing && $existing->is_final) {
                return response()->json(['status' => false, 'message' => 'Asesmen sudah difinalisasi'], 403);
            }

            $skorPilihan = implode(',', $request->skor_pilihan);

            $asesmen = AmiAsesmen::updateOrCreate(
                [
                    'ami_sk_auditor_id' => $request->ami_sk_auditor_id,
                    'ami_indikator_id' => $request->ami_indikator_id,
                ],
                [
                    'skor_pilihan' => $skorPilihan,
                    'catatan_asesor' => $request->catatan_asesor,
                    'assessed_by' => $user->id,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Skor tersimpan',
                'data' => $asesmen,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Finalisasi semua asesmen per SK (hanya Auditor Ketua)
     */
    public function finalize(Request $request, $skId)
    {
        try {
            DB::beginTransaction();

            $sk = AmiSkAuditor::findOrFail($skId);
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);
            $isKetua = $sk->isKetuaAuditor($user->id);

            if (!$isAdmin && !$isKetua) {
                return response()->json(['status' => false, 'message' => 'Hanya Auditor Ketua yang dapat memfinalisasi'], 403);
            }

            // Ambil semua asesmen yang belum final
            $asesmens = AmiAsesmen::where('ami_sk_auditor_id', $skId)
                ->where('is_final', false)
                ->get();

            if ($asesmens->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Tidak ada asesmen yang perlu difinalisasi'], 400);
            }

            foreach ($asesmens as $asesmen) {
                $asesmen->update([
                    'is_final' => true,
                    'finalized_at' => now(),
                    'finalized_by' => $user->id,
                ]);
            }

            AmiAuditTrail::create([
                'user_id' => $user->id,
                'action' => 'finalize_asesmen',
                'model_type' => AmiSkAuditor::class,
                'model_id' => $sk->id,
                'data_after' => ['finalized_count' => $asesmens->count()],
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => $asesmens->count() . ' asesmen berhasil difinalisasi',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Get progress for online exam UI
     */
    public function getProgress($skId)
    {
        $sk = AmiSkAuditor::findOrFail($skId);
        $indikators = AmiIndikator::where('ami_sk_auditor_id', $skId)
            ->where('is_active', true)
            ->get();
            
        $asesmens = AmiAsesmen::where('ami_sk_auditor_id', $skId)
            ->get()
            ->keyBy('ami_indikator_id');

        $total = $indikators->count();
        $answered = 0;
        $statuses = [];

        foreach ($indikators as $ind) {
            $asesmen = isset($asesmens[$ind->id]) ? $asesmens[$ind->id] : null;
            if ($asesmen && $asesmen->skor_pilihan) {
                $answered++;
                $statuses[$ind->id] = 'answered';
            } else {
                $statuses[$ind->id] = 'empty';
            }
        }

        return response()->json([
            'total' => $total,
            'answered' => $answered,
            'statuses' => $statuses
        ]);
    }
}
