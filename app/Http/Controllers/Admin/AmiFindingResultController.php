<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiFindingResult;
use App\Models\AmiCategory;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiFindingResultController extends Controller
{
    private $uploadDir = 'storage/documents/ami-finding-result/';

    public function index()
    {
        return view('admin.ami-finding-result.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiFindingResult::with(['amiCategory', 'prodi'])->select('ami_finding_results.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('assessment_question', 'LIKE', "%$search%");
                    $query->orWhereHas('amiCategory', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
                    $query->orWhereHas('prodi', function ($q) use ($search) {
                        $q->where('nama', 'LIKE', "%$search%");
                    });
                });
            })
            ->addColumn('category_name', function ($row) {
                return $row->amiCategory ? $row->amiCategory->name : '-';
            })
            ->addColumn('prodi_name', function ($row) {
                return $row->prodi ? $row->prodi->nama : '-';
            })
            ->editColumn('document', function ($row) {
                if ($row->document) {
                    return '<a href="' . asset($this->uploadDir . $row->document) . '" target="_blank" class="btn btn-sm btn-info">View</a>';
                }
                return '-';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li><a class="dropdown-item" href="' . route('admin.ami-finding-result.edit', ['amiFindingResult' => $row->id]) . '">Edit</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Hasil Temuan">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'document'])
            ->toJson();
    }

    public function add()
    {
        $categories = AmiCategory::all();
        $prodis = Prodi::all();
        return view('admin.ami-finding-result.add.index', compact('categories', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'category_id' => 'required',
                'prodi_id' => 'required',
                'assessment_question' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
            ]);

            $result = new AmiFindingResult();
            $result->category_id = $request->category_id;
            $result->prodi_id = $request->prodi_id;
            $result->assessment_question = $request->assessment_question;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $result->document = $fileName;
            }

            $result->save();

            \DB::commit();
            return redirect()->route('admin.ami-finding-result.index')->with('success', 'Berhasil menambahkan Hasil Temuan');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-finding-result.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiFindingResult $amiFindingResult)
    {
        $categories = AmiCategory::all();
        $prodis = Prodi::all();
        return view('admin.ami-finding-result.edit.index', compact('categories', 'prodis', 'amiFindingResult'));
    }

    public function update(AmiFindingResult $amiFindingResult, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'category_id' => 'required',
                'prodi_id' => 'required',
                'assessment_question' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
            ]);

            $amiFindingResult->category_id = $request->category_id;
            $amiFindingResult->prodi_id = $request->prodi_id;
            $amiFindingResult->assessment_question = $request->assessment_question;

            if ($request->hasFile('document')) {
                if ($amiFindingResult->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiFindingResult->document);
                    if (File::exists($oldDocPath)) File::delete($oldDocPath);
                }
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiFindingResult->document = $fileName;
            }

            $amiFindingResult->save();

            \DB::commit();
            return redirect()->route('admin.ami-finding-result.index')->with('success', 'Berhasil mengupdate Hasil Temuan');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-finding-result.edit', ['amiFindingResult' => $amiFindingResult->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $data = AmiFindingResult::findOrFail($request->id);

            if ($data->document) {
                $docPath = public_path($this->uploadDir . $data->document);
                if (File::exists($docPath)) File::delete($docPath);
            }

            $data->delete();
            \DB::commit();
            return ['status' => true, 'type' => 'success', 'message' => 'Success'];
        } catch (\Throwable $th) {
            \DB::rollback();
            return ['status' => false, 'type' => 'error', 'message' => $th->getMessage()];
        }
    }
}
