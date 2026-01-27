<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PeriodeAkademikController extends Controller
{
    public function index()
    {
        return view('admin.periode-akademik.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = PeriodeAkademik::select('*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama_periode', 'LIKE', "%$search%");
                });
            })
            ->editColumn('is_active', function ($row) {
                $status = $row->is_active ? 'Aktif' : 'Tidak Aktif';
                $class = $row->is_active ? 'success' : 'danger';
                return '<span class="badge bg-label-' . $class . '">' . $status . '</span>';
            })
            ->editColumn('tanggal_mulai', function ($row) {
                return $row->tanggal_mulai ? $row->tanggal_mulai->format('d-m-Y') : '-';
            })
            ->editColumn('tanggal_selesai', function ($row) {
                return $row->tanggal_selesai ? $row->tanggal_selesai->format('d-m-Y') : '-';
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
                                        data-nama_periode="' . $row->nama_periode . '"
                                        data-tanggal_mulai="' . optional($row->tanggal_mulai)->format('Y-m-d') . '"
                                        data-tanggal_selesai="' . optional($row->tanggal_selesai)->format('Y-m-d') . '"
                                        data-is_active="' . $row->is_active . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->nama_periode . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'is_active'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'nama_periode' => 'required',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Jika di set aktif, matikan periode lain (opsional, tergantung logic bisnis)
            if ($data['is_active']) {
                PeriodeAkademik::query()->update(['is_active' => 0]);
            }

            PeriodeAkademik::create($data);

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->nama_periode
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
                'nama_periode' => 'required',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            $dataStore = PeriodeAkademik::findOrFail($request->id);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Jika di set aktif, matikan periode lain (opsional)
            if ($data['is_active']) {
                PeriodeAkademik::where('id', '!=', $request->id)->update(['is_active' => 0]);
            }

            $dataStore->update($data);

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success update data'
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

            $dataStore = PeriodeAkademik::findOrFail($request->id);
            $dataStore->delete();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success delete data',
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
