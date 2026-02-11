<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Ami\AmiModeSwitcherController;
use App\Models\AmiPeriode;
use App\Models\AmiSkAuditor;
use Illuminate\Support\Facades\Auth;

class AmiDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);

        $periodeAktif = AmiPeriode::where('status', 'aktif')->count();
        $totalSk = AmiSkAuditor::count();
        $skAktif = AmiSkAuditor::where('status', 'aktif')->count();
        $skSelesai = AmiSkAuditor::where('status', 'selesai')->count();

        $mySk = 0;
        $currentMode = null;
        if (!$isAdmin) {
            $currentMode = AmiModeSwitcherController::getCurrentMode();
            $mySk = AmiSkAuditor::where(function ($q) use ($user) {
                $q->where('auditor_ketua_id', $user->id)
                  ->orWhereHas('anggotas', function ($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })->count();
        }

        return view('admin.ami.dashboard.index', compact(
            'periodeAktif', 'totalSk', 'skAktif', 'skSelesai', 'mySk', 'isAdmin', 'currentMode'
        ));
    }
}
