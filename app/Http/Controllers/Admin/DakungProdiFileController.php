<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DakungProdiCategory;
use App\Models\DakungProdiFile;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\UploadDakungToGDriveJob;

class DakungProdiFileController extends Controller
{
    public function index(DakungProdiCategory $dakungProdiCategory)
    {
        return view('admin.dakung-prodi.file.index', compact('dakungProdiCategory'));
    }

    public function data(DakungProdiCategory $dakungProdiCategory, Request $request)
    {
        $search = request('search.value');
        $data   = DakungProdiFile::where('dakung_prodi_category_id', $dakungProdiCategory->id);

        return DataTables::of($data)
            ->addIndexColumn()
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'LIKE', "%$search%");
                    $query->orWhere('original_name', 'LIKE', "%$search%");
                });
            })
            ->editColumn('path', function ($row) {
                return '<a href="' . asset($row->path) . '" target="_blank">Lihat File</a>';
            })
            ->addColumn('upload_status', function ($row) {
                if ($row->upload_status === 'pending') {
                    return '<span class="badge bg-warning">Menunggu Upload Drive</span>';
                } else if ($row->upload_status === 'uploaded') {
                    return '<span class="badge bg-success">Tersimpan di Drive</span>';
                } else if ($row->upload_status === 'failed') {
                    return '<span class="badge bg-danger">Gagal Upload Drive</span>';
                }
                return '';
            })
            ->addColumn('action', function ($row) use ($dakungProdiCategory) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-name="' . $row->name . '"
                                        >Edit Info</button></li>
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
            ->rawColumns(['action', 'path', 'upload_status'])
            ->toJson();
    }

    private function getPublicDirectory($subpath = '')
    {
        if (is_dir(base_path('../public_html'))) {
            $base = base_path('../public_html');
        } else {
            $base = public_path();
        }
        return $subpath ? $base . '/' . ltrim($subpath, '/\\') : $base;
    }

    private function deletePhysicalFile($path)
    {
        if (!$path) return;
        $possiblePaths = [
            base_path('../public_html/' . $path),
            public_path($path),
        ];
        foreach ($possiblePaths as $filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    public function store(DakungProdiCategory $dakungProdiCategory, Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string',
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB
            ]);

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $size = $file->getSize();
            
            $filename = time() . '_' . $originalName;
            $uploadDir = $this->getPublicDirectory('documents/dakung-prodi');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $path = 'documents/dakung-prodi/' . $filename;

            $dataStore = new DakungProdiFile();
            $dataStore->dakung_prodi_category_id = $dakungProdiCategory->id;
            $dataStore->name = $request->name;
            $dataStore->path = $path;
            $dataStore->original_name = $originalName;
            $dataStore->size = $size;
            $dataStore->upload_status = 'pending';
            $dataStore->save();

            // Dispatch job to upload to GDrive
            dispatch(new UploadDakungToGDriveJob($dataStore->id));

            DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil upload file ' . $request->name,
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

    public function update(DakungProdiCategory $dakungProdiCategory, Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id'   => 'required',
                'name' => 'required|string',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            ]);

            $dataStore = DakungProdiFile::findOrFail($request->id);
            $dataStore->name = $request->name;

            if ($request->hasFile('file')) {
                // Delete old file
                $this->deletePhysicalFile($dataStore->path);

                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();
                
                $filename = time() . '_' . $originalName;
                $uploadDir = $this->getPublicDirectory('documents/dakung-prodi');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $file->move($uploadDir, $filename);
                $path = 'documents/dakung-prodi/' . $filename;

                $dataStore->path = $path;
                $dataStore->original_name = $originalName;
                $dataStore->size = $size;
                $dataStore->upload_status = 'pending';
                $dataStore->gdrive_file_id = null;
            }

            $dataStore->save();

            if ($request->hasFile('file')) {
                dispatch(new UploadDakungToGDriveJob($dataStore->id));
            }

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

    public function delete(DakungProdiCategory $dakungProdiCategory, Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = DakungProdiFile::findOrFail($request->id);
            $this->deletePhysicalFile($dataStore->path);
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
