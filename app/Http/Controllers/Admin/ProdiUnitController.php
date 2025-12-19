<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiUnit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProdiUnitController extends Controller
{
    public function index()
    {
        return view('admin.prodi-unit.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = ProdiUnit::select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('keterangan', 'LIKE', "%$search%");
                });
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
                                        data-keterangan="' . $row->keterangan . '"
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
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'nama' => 'required',
                'keterangan' => 'nullable'
            ]);

            $dataStore = new ProdiUnit();
            $dataStore->nama = $request->nama;
            $dataStore->keterangan = $request->keterangan;
            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->nama
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
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
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'nama' => 'required',
                'keterangan' => 'nullable'
            ]);

            $dataStore = ProdiUnit::findOrFail($request->id);
            $dataStore->nama = $request->nama;
            $dataStore->keterangan = $request->keterangan;
            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success'
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
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
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = ProdiUnit::findOrFail($request->id);
            $dataStore->delete();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
