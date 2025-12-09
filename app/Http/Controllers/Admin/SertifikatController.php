<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentAccreditation;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SertifikatController extends Controller
{
    public function indexSertifikat()
    {
        $prodi = Prodi::all();
        return view('admin.accreditation-sertifikat.index', compact('prodi'));
    }

    public function dataSertifikat(Request $request)
    {
        $search = request('search.value');
        $data = DocumentAccreditation::join('prodis', 'document_accreditations.prodi_id', '=', 'prodis.id')
            ->select('document_accreditations.*', 'prodis.nama as prodi_name');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('prodis.nama', 'LIKE', "%$search%");
                });
            })
            ->addColumn('prodi_name', function ($row) {
                return $row->prodi_name;
            })
            ->addColumn('link_file', function ($row) {
                return '<a class="btn btn-primary" href="' . $row->link_file . '" target="_blank">Link Priview</a>';
            })
            ->addColumn('view', function ($row) {
                return '<a class="btn btn-primary" href="' . $row->view . '" target="_blank">link View</a>';
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
                                        data-prodi_id="' . $row->prodi_id . '"
                                        data-link_file="' . $row->link_file . '"
                                        data-view="' . $row->view . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="name" value="' . $row->prodi_name . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'prodi_name', 'link_file', 'view'])
            ->toJson();
    }

    public function storeSertifikat(Request $request)
    {
        try {
            \DB::beginTransaction();

            $request->validate([
                'prodi_id'           => 'required|exists:prodis,id',
                'link_file'          => 'required|string',
                'view'               => 'required|string',
            ]);
            $sertifikat = Prodi::find($request->prodi_id);
            $dataStore                     = new DocumentAccreditation();
            $dataStore->prodi_id           = $request->prodi_id;
            $dataStore->link_file          = $request->link_file;
            $dataStore->view               = $request->view;
            $dataStore->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil menambahkan data sertifikat  ' . $sertifikat->nama,
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

    public function updateSertifikat(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id'                 => 'required',
                'prodi_id'           => 'required|exists:prodis,id',
                'link_file'          => 'required|string',
                'view'               => 'required|string',
            ]);

            $dataStore                     = DocumentAccreditation::findOrFail($request->id);
            $dataStore->prodi_id           = $request->prodi_id;
            $dataStore->link_file          = $request->link_file;
            $dataStore->view               = $request->view;
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

    public function deleteSertifikat(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = DocumentAccreditation::findOrFail($request->id);
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
