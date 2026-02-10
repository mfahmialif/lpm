<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiLaporanKinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AmiLaporanKinerjaController extends Controller
{
    public function index($skId)
    {
        $sk = AmiSkAuditor::with(['periode', 'unit'])->findOrFail($skId);
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        if (!$isAdmin && !$sk->isAuditee($user->id) && !$sk->isAuditor($user->id)) {
            abort(403);
        }

        $laporan = AmiLaporanKinerja::where('ami_sk_auditor_id', $skId)->first();
        $canEdit = $isAdmin || ($sk->isAuditee($user->id) && $sk->status === 'aktif');
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
