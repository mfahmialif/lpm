<?php

namespace App\Http\Controllers\Admin\Ami;

use App\Http\Controllers\Controller;
use App\Models\AmiUnitAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AmiUnitAuditController extends Controller
{
    public function index()
    {
        return view('admin.ami.unit-audit.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiUnitAudit::select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('kode', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jenis_badge', function ($row) {
                $badges = [
                    'fakultas' => 'bg-success',
                    'prodi' => 'bg-primary',
                    'unit' => 'bg-info',
                    'lembaga' => 'bg-warning',
                ];
                $badgeClass = isset($badges[$row->jenis]) ? $badges[$row->jenis] : 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->jenis ?? '-') . '</span>';
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
                                    data-kode="' . ($row->kode ?? '') . '"
                                    data-jenis="' . ($row->jenis ?? '') . '"
                                    data-keterangan="' . ($row->keterangan ?? '') . '"
                                    >Edit</button></li>
                                <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->nama . '">
                                    <button type="submit" class="dropdown-item text-danger">
                                        Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'jenis_badge'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'nullable|string|max:50',
                'jenis' => 'nullable|in:fakultas,prodi,unit,lembaga',
                'keterangan' => 'nullable|string',
            ]);

            AmiUnitAudit::create($request->only(['nama', 'kode', 'jenis', 'keterangan']));

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan unit audit ' . $request->nama
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'nama' => 'required|string|max:255',
                'kode' => 'nullable|string|max:50',
                'jenis' => 'nullable|in:fakultas,prodi,unit,lembaga',
                'keterangan' => 'nullable|string',
            ]);

            $unit = AmiUnitAudit::findOrFail($request->id);
            $unit->update($request->only(['nama', 'kode', 'jenis', 'keterangan']));

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil mengupdate unit audit'
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $unit = AmiUnitAudit::findOrFail($request->id);
            $unit->delete();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menghapus unit audit',
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }
}
