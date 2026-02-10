<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiPeriode;
use App\Models\AmiAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiPeriodeController extends Controller
{
    public function index()
    {
        return view('admin.ami.periode.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiPeriode::select('*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('tahun_mulai', 'LIKE', "%$search%");
                });
            })
            ->addColumn('periode', function ($row) {
                return $row->tahun_mulai . '/' . $row->tahun_selesai;
            })
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'draft' => 'bg-secondary',
                    'aktif' => 'bg-success',
                    'selesai' => 'bg-primary',
                ];
                $badgeClass = isset($badges[$row->status]) ? $badges[$row->status] : 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('jumlah_sk', function ($row) {
                return $row->skAuditors()->count();
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li>
                                <button class="dropdown-item edit-record-button"
                                    data-id="' . $row->id . '"
                                    data-nama="' . $row->nama . '"
                                    data-tahun_mulai="' . $row->tahun_mulai . '"
                                    data-tahun_selesai="' . $row->tahun_selesai . '"
                                    data-status="' . $row->status . '"
                                    data-deskripsi="' . e($row->deskripsi) . '"
                                    >Edit</button></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->nama . '">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'status_badge'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'nama' => 'required|string|max:255',
                'tahun_mulai' => 'required|digits:4',
                'tahun_selesai' => 'required|digits:4|gte:tahun_mulai',
                'status' => 'required|in:draft,aktif,selesai',
                'deskripsi' => 'nullable|string',
            ]);

            $data = AmiPeriode::create($request->only(['nama', 'tahun_mulai', 'tahun_selesai', 'status', 'deskripsi']));

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'create_periode',
                'model_type' => AmiPeriode::class,
                'model_id' => $data->id,
                'data_after' => $data->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil menambahkan Periode AMI'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'nama' => 'required|string|max:255',
                'tahun_mulai' => 'required|digits:4',
                'tahun_selesai' => 'required|digits:4|gte:tahun_mulai',
                'status' => 'required|in:draft,aktif,selesai',
                'deskripsi' => 'nullable|string',
            ]);

            $data = AmiPeriode::findOrFail($request->id);
            $before = $data->toArray();
            $data->update($request->only(['nama', 'tahun_mulai', 'tahun_selesai', 'status', 'deskripsi']));

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'update_periode',
                'model_type' => AmiPeriode::class,
                'model_id' => $data->id,
                'data_before' => $before,
                'data_after' => $data->toArray(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil mengupdate Periode AMI'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = AmiPeriode::findOrFail($request->id);

            AmiAuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'delete_periode',
                'model_type' => AmiPeriode::class,
                'model_id' => $data->id,
                'data_before' => $data->toArray(),
                'ip_address' => $request->ip(),
            ]);

            $data->delete();
            DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Berhasil menghapus Periode AMI'];
        } catch (\Throwable $th) {
            DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }
}
