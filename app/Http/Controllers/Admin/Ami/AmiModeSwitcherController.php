<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiSkAuditor;
use App\Models\AmiSkAuditorAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmiModeSwitcherController extends Controller
{
    /**
     * Switch AMI mode between auditee and auditor.
     * Stores the selected mode in the session.
     */
    public function switchMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:auditee,auditor',
        ]);

        session(['ami_mode' => $request->mode]);

        return redirect()->back()->with('success', 'Mode AMI berhasil diubah ke ' . ucfirst($request->mode));
    }

    /**
     * Get the available AMI modes for the current user.
     * Returns an array of available modes (auditee, auditor, or both).
     */
    public static function getAvailableModes()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['unit'])) {
            return [];
        }

        $modes = [];

        // Check if user is auditee in any SK
        $isAuditee = AmiSkAuditorAnggota::where('user_id', $user->id)
            ->where('peran', 'auditee')
            ->exists();

        if ($isAuditee) {
            $modes[] = 'auditee';
        }

        // Check if user is auditor (ketua or anggota) in any SK
        $isAuditorAnggota = AmiSkAuditorAnggota::where('user_id', $user->id)
            ->where('peran', 'auditor_anggota')
            ->exists();

        $isKetuaAuditor = AmiSkAuditor::where('auditor_ketua_id', $user->id)->exists();

        if ($isAuditorAnggota || $isKetuaAuditor) {
            $modes[] = 'auditor';
        }

        return $modes;
    }

    /**
     * Get the current AMI mode from session, with auto-detection fallback.
     */
    public static function getCurrentMode()
    {
        $mode = session('ami_mode');

        if (!$mode) {
            $modes = self::getAvailableModes();
            // Default to auditee if available, otherwise auditor
            $mode = in_array('auditee', $modes) ? 'auditee' : (in_array('auditor', $modes) ? 'auditor' : null);
            if ($mode) {
                session(['ami_mode' => $mode]);
            }
        }

        return $mode;
    }
}
