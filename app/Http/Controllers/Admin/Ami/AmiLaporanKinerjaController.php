<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiLaporanKinerja;
use App\Models\AmiPeriode;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AmiLaporanKinerjaController extends Controller
{
    public function indexSk(Request $request)
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $query = AmiSkAuditor::with(['periode', 'unit', 'ketuaAuditor'])
            ->withCount('laporanKinerjas');

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

        return view('admin.ami.laporan-kinerja.list_sk', compact('skList', 'periodes', 'units'));
    }

    public function index($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        if (!$isAdmin && !$sk->isAuditee($user->id) && !$sk->isAuditor($user->id)) {
            abort(403);
        }

        $laporan = AmiLaporanKinerja::where('ami_sk_auditor_id', $skId)->first();
        $isAuditor = $sk->isAuditor($user->id) || $sk->isKetuaAuditor($user->id);
        $canEdit = $isAdmin || $isAuditor;
        return view('admin.ami.laporan-kinerja.index', compact('sk', 'laporan', 'canEdit'));
    }

    public function store(Request $request, $skId)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'ringkasan' => 'required|string',
                'rencana_tindak_lanjut' => 'nullable|string',
            ]);

            $sk = AmiSkAuditor::findOrFail($skId);
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['admin', 'lpm']);

            if (!$isAdmin && (!$sk->isAuditee($user->id) || $sk->status !== 'aktif')) {
                return redirect()->back()->with('error', 'Tidak memiliki akses');
            }

            AmiLaporanKinerja::updateOrCreate(
                ['ami_sk_auditor_id' => $skId],
                [
                    'ringkasan' => $request->ringkasan,
                    'rencana_tindak_lanjut' => $request->rencana_tindak_lanjut,
                    'submitted_by' => $user->id,
                    'submitted_at' => now(),
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Laporan Kinerja berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
