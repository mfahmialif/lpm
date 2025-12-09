<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmiPeriod;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AmiPeriodController extends Controller
{
    public function index()
    {
        return view('admin.ami-period.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiPeriod::select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('year', 'LIKE', "%$search%");
                    $query->orWhere('start_date', 'LIKE', "%$search%");
                    $query->orWhere('end_date', 'LIKE', "%$search%");
                });
            })
            ->editColumn('is_active', function ($row) {
                return '<span class="badge bg-' . ($row->is_active ? 'success' : 'danger') . '">' . ($row->is_active ? 'Aktif' : 'Tidak Aktif') . '</span>';
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
                                        data-year="' . $row->year . '"
                                        data-start_date="' . $row->start_date . '"
                                        data-end_date="' . $row->end_date . '"
                                        data-is_active="' . $row->is_active . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->year . '">
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
            \DB::beginTransaction();
            $request->validate([
                'year' => 'required',
                'is_active' => 'nullable',
                'end_date' => 'nullable',
                'start_date' => 'nullable',
            ]);

            if ($request->is_active == 1) {
                AmiPeriod::where('is_active', 1)->update([
                    'is_active' => 0
                ]);
            }

            $dataStore = new AmiPeriod();
            $dataStore->year = $request->year;
            $dataStore->is_active = $request->is_active;
            $dataStore->end_date = $request->end_date;
            $dataStore->start_date = $request->start_date;
            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->year
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
                'year' => 'required',
                'is_active' => 'nullable',
                'end_date' => 'nullable',
                'start_date' => 'nullable',
            ]);

            if ($request->is_active == 1) {
                AmiPeriod::where('is_active', 1)->update([
                    'is_active' => 0
                ]);
            }

            $dataStore = AmiPeriod::findOrFail($request->id);
            $dataStore->year = $request->year;
            $dataStore->is_active = $request->is_active;
            $dataStore->end_date = $request->end_date;
            $dataStore->start_date = $request->start_date;
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

            $dataStore = AmiPeriod::findOrFail($request->id);
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
