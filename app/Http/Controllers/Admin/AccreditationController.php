<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccreditationController extends Controller
{
    public function index()
    {
        $prodis = Prodi::orderBy('strata', 'asc')->get();
        return view('admin.accreditation.index', compact('prodis'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data   = Accreditation::join('prodis', 'accreditations.prodi_id', '=', 'prodis.id')
            ->select('accreditations.*', 'prodis.nama as prodi_name');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('accreditations.name', 'LIKE', "%$search%");
                    $query->orWhere('accreditations.year', 'LIKE', "%$search%");
                    $query->orWhere('accreditations.result', 'LIKE', "%$search%");
                    $query->orWhere('accreditations.result_description', 'LIKE', "%$search%");
                    $query->orWhere('prodis.nama', 'LIKE', "%$search%");
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
                                    <a class="dropdown-item" href="' . route('admin.accreditation.requirement.index', $row->id) . '"
                                        >Requirement / Documents</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-prodi_id="' . $row->prodi_id . '"
                                        data-name="' . $row->name . '"
                                        data-year="' . $row->year . '"
                                        data-result="' . $row->result . '"
                                        data-result_description="' . $row->result_description . '"
                                        data-status="' . $row->status . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->name . '">
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
                'name'               => 'required',
                'prodi_id'           => 'required|exists:prodis,id',
                'year'               => 'required|integer',
                'name'               => 'required|string',
                'status'             => 'required|in:ongoing,completed',
                'result'             => 'required|in:A,B,C,Not Accredited',
                'result_description' => 'nullable|string',
            ]);

            $dataStore                     = new Accreditation();
            $dataStore->prodi_id           = $request->prodi_id;
            $dataStore->year               = $request->year;
            $dataStore->name               = $request->name;
            $dataStore->status             = $request->status;
            $dataStore->result             = $request->result;
            $dataStore->result_description = $request->result_description;
            $dataStore->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->name,
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message'   => implode(' ', collect($e->errors())->flatten()->toArray()),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id'                 => 'required',
                'prodi_id'           => 'required|exists:prodis,id',
                'year'               => 'required|integer',
                'name'               => 'required|string',
                'status'             => 'required|in:ongoing,completed',
                'result'             => 'required|in:A,B,C,Not Accredited',
                'result_description' => 'nullable|string',
            ]);

            $dataStore                     = Accreditation::findOrFail($request->id);
            $dataStore->prodi_id           = $request->prodi_id;
            $dataStore->year               = $request->year;
            $dataStore->name               = $request->name;
            $dataStore->status             = $request->status;
            $dataStore->result             = $request->result;
            $dataStore->result_description = $request->result_description;
            $dataStore->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 422,
                'error'   => implode(' ', collect($e->errors())->flatten()->toArray()),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
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

            $dataStore = Accreditation::findOrFail($request->id);
            $dataStore->delete();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
