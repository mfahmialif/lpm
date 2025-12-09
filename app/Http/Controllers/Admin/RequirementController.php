<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RequirementController extends Controller
{
    public function index(Accreditation $accreditation, Request $request)
    {
        if ($request->filled('parent_id')) {
            $parent = Requirement::findOrFail($request->parent_id);
        }
        $parentId = $request->filled('parent_id') ? $request->parent_id : null;

        $requirement = Requirement::where('id', $parentId)
            ->first();

        return view('admin.accreditation.requirement.index', compact('accreditation', 'parentId', 'requirement'));
    }

    public function data(Accreditation $accreditation, Request $request)
    {
        $search = request('search.value');
        $data   = Requirement::where('accreditation_id', $accreditation->id)
            ->select('*');
        return DataTables::of($data)
            ->filter(function ($query) use ($search, $request) {
                $query->when($request->filled('parent_id'), function ($query) use ($request) {
                    $query->where('parent_id', $request->parent_id);
                }, function ($query) use ($search) {
                    $query->whereNull('parent_id');
                });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('code', 'LIKE', "%$search%");
                    $query->orWhere('title', 'LIKE', "%$search%");
                    $query->orWhere('description', 'LIKE', "%$search%");
                    $query->orWhere('link', 'LIKE', "%$search%");
                });
            })
            ->editColumn('link', function ($row) {
                if ($row->link) {
                    return '<a href="' . $row->link . '" target="_blank">' . $row->link . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($row) use ($accreditation) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a href="' . route('admin.accreditation.requirement.index', ['accreditation' => $accreditation, 'parent_id' => $row->id]) . '" class="dropdown-item"
                                    >Sub Requirement</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-parent_id="' . $row->parent_id . '"
                                        data-code="' . $row->code . '"
                                        data-title="' . $row->title . '"
                                        data-description="' . $row->description . '"
                                        data-link="' . $row->link . '"
                                        data-order_index="' . $row->order_index . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->title . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'link'])
            ->toJson();
    }

    public function store(Accreditation $accreditation, Request $request)
    {
        try {
            \DB::beginTransaction();

            $request->validate([
                'parent_id'   => 'nullable|exists:requirements,id',
                'code'        => 'nullable|string',
                'title'       => 'required|string',
                'description' => 'nullable|string',
                'link'        => 'nullable|string',
            ]);

            $maxOrder = Requirement::where('accreditation_id', $request->accreditation_id)
                ->max('order_index');

            $dataStore                   = new Requirement();
            $dataStore->accreditation_id = $accreditation->id;
            $dataStore->parent_id        = $request->parent_id;
            $dataStore->code             = $request->code;
            $dataStore->title            = $request->title;
            $dataStore->description      = $request->description;
            $dataStore->link             = $request->link;
            $dataStore->order_index      = $maxOrder ? $maxOrder + 1 : 1;
            $dataStore->save();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->title,
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => implode(' ', collect($e->errors())->flatten()->toArray()),
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

    public function update(Accreditation $accreditation, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id'          => 'required',
                'parent_id'   => 'nullable|exists:requirements,id',
                'code'        => 'nullable|string',
                'title'       => 'required|string',
                'description' => 'nullable|string',
                'link'        => 'nullable|string',
            ]);

            $dataStore              = Requirement::findOrFail($request->id);
            $dataStore->parent_id   = $request->parent_id;
            $dataStore->code        = $request->code;
            $dataStore->title       = $request->title;
            $dataStore->description = $request->description;
            $dataStore->link        = $request->link;
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
                'status'  => false,
                'type'    => 'error',
                'message' => implode(' ', collect($e->errors())->flatten()->toArray()),
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

    public function delete(Accreditation $accreditation, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = Requirement::findOrFail($request->id);
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
