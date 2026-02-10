<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('admin.unit.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = Unit::select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('fakultas', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jenis_badge', function ($row) {
                $badgeClass = match ($row->jenis) {
                    'Prodi' => 'bg-primary',
                    'Fakultas' => 'bg-success',
                    'Institusi' => 'bg-info',
                    default => 'bg-secondary',
                };
                return '<span class="badge ' . $badgeClass . '">' . $row->jenis . '</span>';
            })
            ->addColumn('jenjang_badge', function ($row) {
                $badgeClass = match ($row->jenjang) {
                    'S1' => 'bg-primary',
                    'S2' => 'bg-warning',
                    'S3' => 'bg-danger',
                    default => 'bg-secondary',
                };
                return '<span class="badge ' . $badgeClass . '">' . $row->jenjang . '</span>';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-nama="' . $row->nama . '"
                                        data-jenis="' . $row->jenis . '"
                                        data-fakultas="' . ($row->fakultas ?? '') . '"
                                        data-jenjang="' . $row->jenjang . '"
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
                return $actionButtons;
            })
            ->rawColumns(['action', 'jenis_badge', 'jenjang_badge'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'nama' => 'required|string|max:255',
                'jenis' => 'required|in:Prodi,Fakultas,Institusi',
                'fakultas' => 'nullable|string|max:255',
                'jenjang' => 'required|in:S1,S2,S3',
            ]);

            $dataStore = new Unit();
            $dataStore->nama = $request->nama;
            $dataStore->jenis = $request->jenis;
            $dataStore->fakultas = $request->fakultas;
            $dataStore->jenjang = $request->jenjang;
            $dataStore->save();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->nama
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
                'jenis' => 'required|in:Prodi,Fakultas,Institusi',
                'fakultas' => 'nullable|string|max:255',
                'jenjang' => 'required|in:S1,S2,S3',
            ]);

            $dataStore = Unit::findOrFail($request->id);
            $dataStore->nama = $request->nama;
            $dataStore->jenis = $request->jenis;
            $dataStore->fakultas = $request->fakultas;
            $dataStore->jenjang = $request->jenjang;
            $dataStore->save();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success'
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

            $dataStore = Unit::findOrFail($request->id);
            $dataStore->delete();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
