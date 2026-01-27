<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DosenController extends Controller
{
    public function index()
    {
        return view('admin.dosen.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = Dosen::select('*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('nidn', 'LIKE', "%$search%");
                    $query->orWhere('email', 'LIKE', "%$search%");
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
                                        data-nidn="' . $row->nidn . '"
                                        data-kode="' . $row->kode . '"
                                        data-email="' . $row->email . '"
                                        data-hp="' . $row->hp . '"
                                        data-gelar_depan="' . $row->gelar_depan . '"
                                        data-gelar_belakang="' . $row->gelar_belakang . '"
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
                'nidn' => 'nullable|unique:mst_dosen,nidn',
                'email' => 'nullable|email|unique:mst_dosen,email',
                'hp' => 'nullable',
                'kode' => 'nullable'
            ]);

            $dataStore = new Dosen();
            $dataStore->nama = $request->nama;
            $dataStore->nidn = $request->nidn;
            $dataStore->kode = $request->kode;
            $dataStore->gelar_depan = $request->gelar_depan;
            $dataStore->gelar_belakang = $request->gelar_belakang;
            $dataStore->email = $request->email;
            $dataStore->hp = $request->hp;

            // Optional fields can be added here

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
                'nidn' => 'nullable|unique:mst_dosen,nidn,' . $request->id,
                'email' => 'nullable|email|unique:mst_dosen,email,' . $request->id,
            ]);

            $dataStore = Dosen::findOrFail($request->id);
            $dataStore->nama = $request->nama;

            if ($request->has('nidn')) $dataStore->nidn = $request->nidn;
            if ($request->has('kode')) $dataStore->kode = $request->kode;
            if ($request->has('gelar_depan')) $dataStore->gelar_depan = $request->gelar_depan;
            if ($request->has('gelar_belakang')) $dataStore->gelar_belakang = $request->gelar_belakang;
            if ($request->has('email')) $dataStore->email = $request->email;
            if ($request->has('hp')) $dataStore->hp = $request->hp;

            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success update data'
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

            $dataStore = Dosen::findOrFail($request->id);
            $dataStore->delete();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success delete data',
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
