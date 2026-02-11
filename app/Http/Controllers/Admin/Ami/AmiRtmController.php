<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiRtm;
use App\Models\AmiRtmSk;
use App\Models\AmiRtmTemuan;
use App\Models\AmiSkAuditor;
use App\Models\AmiHasilTemuan;
use App\Models\AmiAuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiRtmController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        return view('admin.ami.rtm.index', compact('isAdmin'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiRtm::with(['pimpinan', 'skAuditors.unit'])
            ->select('ami_rtms.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('kode_rtm', 'LIKE', "%$search%");
                    $q->orWhereHas('pimpinan', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%$search%");
                    });
                });
            })
            ->addColumn('pimpinan_nama', function ($row) {
                return $row->pimpinan ? $row->pimpinan->name : '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->tanggal_rtm->format('d/m/Y');
            })
            ->addColumn('unit_list', function ($row) {
                $units = $row->skAuditors->map(function ($sk) {
                    return $sk->unit ? $sk->unit->nama : '-';
                })->unique();
                if ($units->isEmpty()) return '<span class="text-muted">-</span>';
                return $units->map(function ($n) {
                    return '<span class="badge bg-label-primary me-1 mb-1">' . e($n) . '</span>';
                })->implode('');
            })
            ->addColumn('jumlah_sk', function ($row) {
                return $row->skAuditors->count();
            })
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'draft' => 'bg-secondary',
                    'sah' => 'bg-success',
                    'ditutup' => 'bg-primary',
                ];
                $badgeClass = isset($badges[$row->status]) ? $badges[$row->status] : 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . strtoupper($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li><a class="dropdown-item" href="' . route('admin.ami.rtm.show', $row->id) . '"><i class="ti ti-eye me-2"></i>Detail</a></li>
                            ' . ($row->status === 'draft' ? '<li><a class="dropdown-item" href="' . route('admin.ami.rtm.edit', $row->id) . '"><i class="ti ti-pencil me-2"></i>Edit</a></li>' : '') . '
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->kode_rtm . '">
                                    <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'status_badge', 'unit_list'])
            ->toJson();
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $skList = AmiSkAuditor::with(['periode', 'unit'])
            ->whereIn('status', ['aktif', 'terkunci', 'selesai'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.ami.rtm.form', compact('users', 'skList'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'kode_rtm' => 'required|string|unique:ami_rtms,kode_rtm',
                'tanggal_rtm' => 'required|date',
                'pimpinan_id' => 'nullable|exists:users,id',
                'catatan_umum' => 'nullable|string',
                'sk_ids' => 'required|array|min:1',
                'sk_ids.*' => 'exists:ami_sk_auditors,id',
            ]);

            $rtm = AmiRtm::create([
                'kode_rtm' => $request->kode_rtm,
                'tanggal_rtm' => $request->tanggal_rtm,
                'pimpinan_id' => $request->pimpinan_id,
                'catatan_umum' => $request->catatan_umum,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // Attach SK Auditors
            $rtm->skAuditors()->attach($request->sk_ids);

            // Auto-populate temuan from linked SKs
            $this->syncTemuanFromSks($rtm);

            DB::commit();
            return redirect()->route('admin.ami.rtm.show', $rtm->id)
                ->with('success', 'RTM berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $rtm = AmiRtm::findOrFail($id);

        // Auto-sync hasil temuan from linked SKs
        $this->syncTemuanFromSks($rtm);

        // Reload with all relationships
        $rtm->load([
            'pimpinan',
            'createdBy',
            'skAuditors.unit',
            'skAuditors.periode',
            'rtmTemuans.hasilTemuan',
            'rtmTemuans.skAuditor.unit',
            'rtmTemuans.penanggungJawab',
        ]);

        $user = Auth::user();
        $isAdmin = in_array($user->role, ['admin', 'lpm']);
        $canEdit = $isAdmin && $rtm->status === 'draft';
        $users = User::orderBy('name')->get();

        return view('admin.ami.rtm.show', compact('rtm', 'isAdmin', 'canEdit', 'users'));
    }

    public function edit($id)
    {
        $rtm = AmiRtm::with('skAuditors')->findOrFail($id);
        if ($rtm->status !== 'draft') {
            return redirect()->route('admin.ami.rtm.show', $id)
                ->with('error', 'RTM yang sudah disahkan tidak dapat diedit');
        }

        $users = User::orderBy('name')->get();
        $skList = AmiSkAuditor::with(['periode', 'unit'])
            ->whereIn('status', ['aktif', 'terkunci', 'selesai'])
            ->orderBy('created_at', 'desc')
            ->get();
        $selectedSkIds = $rtm->skAuditors->pluck('id')->toArray();

        return view('admin.ami.rtm.form', compact('rtm', 'users', 'skList', 'selectedSkIds'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $rtm = AmiRtm::findOrFail($id);
            if ($rtm->status !== 'draft') {
                return redirect()->back()->with('error', 'RTM yang sudah disahkan tidak dapat diedit');
            }

            $request->validate([
                'kode_rtm' => 'required|string|unique:ami_rtms,kode_rtm,' . $id,
                'tanggal_rtm' => 'required|date',
                'pimpinan_id' => 'nullable|exists:users,id',
                'catatan_umum' => 'nullable|string',
                'sk_ids' => 'required|array|min:1',
                'sk_ids.*' => 'exists:ami_sk_auditors,id',
            ]);

            $rtm->update([
                'kode_rtm' => $request->kode_rtm,
                'tanggal_rtm' => $request->tanggal_rtm,
                'pimpinan_id' => $request->pimpinan_id,
                'catatan_umum' => $request->catatan_umum,
            ]);

            // Sync SK Auditors
            $rtm->skAuditors()->sync($request->sk_ids);

            // Sync temuan
            $this->syncTemuanFromSks($rtm);

            DB::commit();
            return redirect()->route('admin.ami.rtm.show', $rtm->id)
                ->with('success', 'RTM berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            AmiRtm::findOrFail($request->id)->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'RTM berhasil dihapus'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    // Save keputusan for a single temuan
    public function saveKeputusan(Request $request, $rtmId)
    {
        try {
            $rtm = AmiRtm::findOrFail($rtmId);
            if ($rtm->status !== 'draft') {
                return response()->json(['status' => false, 'message' => 'RTM sudah disahkan'], 403);
            }

            $request->validate([
                'rtm_temuan_id' => 'required|exists:ami_rtm_temuans,id',
                'keputusan' => 'nullable|string',
                'rencana_tindak_lanjut' => 'nullable|string',
                'penanggung_jawab_id' => 'nullable|exists:users,id',
                'target_selesai' => 'nullable|date',
            ]);

            $rtmTemuan = AmiRtmTemuan::where('ami_rtm_id', $rtmId)
                ->findOrFail($request->rtm_temuan_id);

            $rtmTemuan->update($request->only([
                'keputusan',
                'rencana_tindak_lanjut',
                'penanggung_jawab_id',
                'target_selesai',
            ]));

            return response()->json(['status' => true, 'message' => 'Keputusan tersimpan']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // Update status tindak lanjut
    public function updateStatusTl(Request $request, $rtmId)
    {
        try {
            $request->validate([
                'rtm_temuan_id' => 'required|exists:ami_rtm_temuans,id',
                'status_tindak_lanjut' => 'required|in:open,on_progress,selesai',
            ]);

            $rtmTemuan = AmiRtmTemuan::where('ami_rtm_id', $rtmId)
                ->findOrFail($request->rtm_temuan_id);

            $rtmTemuan->update(['status_tindak_lanjut' => $request->status_tindak_lanjut]);

            return response()->json(['status' => true, 'message' => 'Status tindak lanjut diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // Sahkan RTM
    public function sahkan($id)
    {
        try {
            DB::beginTransaction();
            $rtm = AmiRtm::with('rtmTemuans')->findOrFail($id);

            if ($rtm->status !== 'draft') {
                return redirect()->back()->with('error', 'RTM sudah disahkan');
            }

            // Validate all temuan have keputusan, PIC, target
            $incomplete = $rtm->rtmTemuans->filter(function ($t) {
                return !$t->keputusan || !$t->penanggung_jawab_id || !$t->target_selesai;
            });

            if ($incomplete->count() > 0) {
                return redirect()->back()->with('error',
                    'Masih ada ' . $incomplete->count() . ' temuan yang belum lengkap (keputusan/penanggung jawab/target waktu)');
            }

            $rtm->update(['status' => 'sah']);

            DB::commit();
            return redirect()->back()->with('success', 'RTM berhasil disahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Tutup RTM
    public function tutup($id)
    {
        try {
            DB::beginTransaction();
            $rtm = AmiRtm::with('rtmTemuans')->findOrFail($id);

            if ($rtm->status !== 'sah') {
                return redirect()->back()->with('error', 'Hanya RTM berstatus SAH yang dapat ditutup');
            }

            $openItems = $rtm->rtmTemuans->where('status_tindak_lanjut', 'open')->count();
            if ($openItems > 0) {
                return redirect()->back()->with('error',
                    'Masih ada ' . $openItems . ' tindak lanjut berstatus OPEN');
            }

            $rtm->update(['status' => 'ditutup']);

            DB::commit();
            return redirect()->back()->with('success', 'RTM berhasil ditutup');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Change status directly
    public function changeStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:draft,sah,ditutup',
            ]);

            $rtm = AmiRtm::findOrFail($id);
            $rtm->update(['status' => $request->status]);

            return response()->json([
                'status' => true,
                'message' => 'Status RTM berhasil diubah menjadi ' . strtoupper($request->status),
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // Helper: Sync hasil temuan records from linked SKs
    private function syncTemuanFromSks(AmiRtm $rtm)
    {
        // Use allRelatedIds to get the linked SK IDs reliably
        $skIds = $rtm->skAuditors()->allRelatedIds();

        if ($skIds->isEmpty()) {
            return;
        }

        // Get all hasil temuan from linked SKs
        $hasilTemuans = AmiHasilTemuan::whereIn('ami_sk_auditor_id', $skIds)->get();

        // Remove records for hasil temuan no longer linked
        AmiRtmTemuan::where('ami_rtm_id', $rtm->id)
            ->whereNotIn('ami_hasil_temuan_id', $hasilTemuans->pluck('id'))
            ->delete();

        // Create records that don't exist yet (preserve existing keputusan)
        foreach ($hasilTemuans as $hasil) {
            $rtmTemuan = AmiRtmTemuan::firstOrCreate(
                [
                    'ami_rtm_id' => $rtm->id,
                    'ami_hasil_temuan_id' => $hasil->id,
                ],
                [
                    'ami_sk_auditor_id' => $hasil->ami_sk_auditor_id,
                    'status_tindak_lanjut' => 'open',
                ]
            );
        }
    }
}
