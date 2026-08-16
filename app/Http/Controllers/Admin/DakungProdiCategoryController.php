<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\DakungProdiCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class DakungProdiCategoryController extends Controller
{
    public function index()
    {
        $accreditations = Accreditation::with('prodi')->orderBy('year', 'desc')->get();
        return view('admin.dakung-prodi.index', compact('accreditations'));
    }

    public function getCategories(Request $request)
    {
        $accreditationId = $request->accreditation_id;
        $categories = DakungProdiCategory::where('accreditation_id', $accreditationId)->orderBy('order_index')->get();
        return response()->json($categories);
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $accreditationId = request('accreditation_id');

        $data = DakungProdiCategory::with('accreditation.prodi')
            ->orderBy('order_index', 'asc')
            ->orderBy('id', 'asc');

        if ($accreditationId) {
            $data->where('accreditation_id', $accreditationId);
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'LIKE', "%$search%");
                    $query->orWhere('kategori', 'LIKE', "%$search%");
                    $query->orWhere('description', 'LIKE', "%$search%");
                    $query->orWhereHas('accreditation', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")
                          ->orWhere('year', 'LIKE', "%$search%")
                          ->orWhereHas('prodi', function ($q2) use ($search) {
                              $q2->where('nama', 'LIKE', "%$search%");
                          });
                    });
                });
            })
            ->addColumn('accreditation_info', function ($row) {
                return $row->accreditation->name . ' - ' . $row->accreditation->prodi->nama . ' (' . $row->accreditation->year . ')';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item" href="' . route('admin.dakung-prodi.file.index', $row->id) . '"
                                        >Manage Files</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-accreditation_id="' . $row->accreditation_id . '"
                                        data-kategori="' . $row->kategori . '"
                                        data-name="' . $row->name . '"
                                        data-description="' . $row->description . '"
                                        data-order_index="' . $row->order_index . '"
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
            DB::beginTransaction();

            $request->validate([
                'accreditation_id' => 'required|exists:accreditations,id',
                'kategori'         => 'nullable|in:LKPS,LED,LKPT,DKPS',
                'name'             => 'required|string',
                'description'      => 'nullable|string',
                'order_index'      => 'nullable|integer',
            ]);

            $maxOrder = DakungProdiCategory::where('accreditation_id', $request->accreditation_id)
                ->max('order_index');

            $dataStore = DakungProdiCategory::firstOrCreate(
                [
                    'accreditation_id' => $request->accreditation_id,
                    'name' => $request->name,
                ],
                [
                    'kategori'    => $request->kategori,
                    'description' => $request->description,
                    'order_index' => $request->order_index ?? ($maxOrder ? $maxOrder + 1 : 1),
                ]
            );

            $message = $dataStore->wasRecentlyCreated 
                ? 'Berhasil menambahkan accordion ' . $request->name
                : 'Accordion ' . $request->name . ' sudah ada, tidak ada data baru yang ditambahkan (gunakan tombol edit untuk mengubahnya).';

            DB::commit();
            return [
                'status'  => true,
                'type'    => $dataStore->wasRecentlyCreated ? 'success' : 'info',
                'message' => $message,
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => implode(' ', collect($e->errors())->flatten()->toArray()),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
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
            DB::beginTransaction();
            $request->validate([
                'id'               => 'required',
                'accreditation_id' => 'required|exists:accreditations,id',
                'kategori'         => 'nullable|in:LKPS,LED,LKPT,DKPS',
                'name'             => 'required|string',
                'description'      => 'nullable|string',
                'order_index'      => 'nullable|integer',
            ]);

            $dataStore                   = DakungProdiCategory::findOrFail($request->id);
            $dataStore->accreditation_id = $request->accreditation_id;
            $dataStore->kategori         = $request->kategori;
            $dataStore->name             = $request->name;
            $dataStore->description      = $request->description;
            if ($request->has('order_index') && $request->order_index !== null) {
                $dataStore->order_index = $request->order_index;
            }
            $dataStore->save();

            DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => implode(' ', collect($e->errors())->flatten()->toArray()),
                'req'     => $request->all(),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
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
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = DakungProdiCategory::findOrFail($request->id);
            // Files will be deleted via cascade in DB, but actual files in storage should be deleted.
            // Let's delete the physical files
            foreach ($dataStore->files as $file) {
                if ($file->path) {
                    $possiblePaths = [
                        base_path('../public_html/' . $file->path),
                        public_path($file->path),
                    ];
                    foreach ($possiblePaths as $filePath) {
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
            }
            $dataStore->delete();

            DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }
}
