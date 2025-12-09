<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiSelfEvaluation;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiSelfEvaluationController extends Controller
{
    private $uploadDir = 'storage/documents/ami-self-evaluation/';

    public function index()
    {
        return view('admin.ami-self-evaluation.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiSelfEvaluation::with(['amiPeriod', 'prodi'])->select('ami_self_evaluations.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('evaluations', 'LIKE', "%$search%");
                    $query->orWhere('filling_guide_name', 'LIKE', "%$search%");
                    $query->orWhereHas('amiPeriod', function ($q) use ($search) {
                        $q->where('year', 'LIKE', "%$search%");
                    });
                    $query->orWhereHas('prodi', function ($q) use ($search) {
                        $q->where('nama', 'LIKE', "%$search%");
                    });
                });
            })
            ->addColumn('ami_period', function ($row) {
                return $row->amiPeriod ? $row->amiPeriod->year : '-';
            })
            ->addColumn('prodi_name', function ($row) {
                return $row->prodi ? $row->prodi->nama : '-';
            })
            ->editColumn('status', function ($row) {
                return '<span class="badge bg-' . ($row->status == 'y' ? 'success' : 'secondary') . '">' . ($row->status == 'y' ? 'Active' : 'Inactive') . '</span>';
            })
            ->editColumn('document', function ($row) {
                if ($row->document) {
                    return '<a href="' . asset($this->uploadDir . $row->document) . '" target="_blank" class="btn btn-sm btn-info">View</a>';
                }
                return '-';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li>
                                <a class="dropdown-item" href="' . route('admin.ami-self-evaluation.edit', ['amiSelfEvaluation' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Evaluasi Diri ' . ($row->prodi ? $row->prodi->nama : '') . '">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'status', 'document'])
            ->toJson();
    }

    public function add()
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-self-evaluation.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'evaluations' => 'nullable',
                'filling_guide_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $evaluation = new AmiSelfEvaluation();
            $evaluation->ami_period_id = $request->ami_period_id;
            $evaluation->prodi_id = $request->prodi_id;
            $evaluation->evaluations = $request->evaluations;
            $evaluation->filling_guide_name = $request->filling_guide_name;
            $evaluation->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $evaluation->document = $fileName;
            }

            $evaluation->save();

            \DB::commit();
            return redirect()->route('admin.ami-self-evaluation.index')->with('success', 'Berhasil menambahkan Evaluasi Diri AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-self-evaluation.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-self-evaluation.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiSelfEvaluation $amiSelfEvaluation)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-self-evaluation.edit.index', compact('periods', 'prodis', 'amiSelfEvaluation'));
    }

    public function update(AmiSelfEvaluation $amiSelfEvaluation, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'evaluations' => 'nullable',
                'filling_guide_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $amiSelfEvaluation->ami_period_id = $request->ami_period_id;
            $amiSelfEvaluation->prodi_id = $request->prodi_id;
            $amiSelfEvaluation->evaluations = $request->evaluations;
            $amiSelfEvaluation->filling_guide_name = $request->filling_guide_name;
            $amiSelfEvaluation->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                if ($amiSelfEvaluation->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiSelfEvaluation->document);
                    if (File::exists($oldDocPath)) {
                        File::delete($oldDocPath);
                    }
                }

                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiSelfEvaluation->document = $fileName;
            }

            $amiSelfEvaluation->save();

            \DB::commit();
            return redirect()->route('admin.ami-self-evaluation.index')->with('success', 'Berhasil mengupdate Evaluasi Diri AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-self-evaluation.edit', ['amiSelfEvaluation' => $amiSelfEvaluation->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-self-evaluation.edit', ['amiSelfEvaluation' => $amiSelfEvaluation->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate(['id' => 'required']);

            $data = AmiSelfEvaluation::findOrFail($request->id);

            if ($data->document) {
                $docPath = public_path($this->uploadDir . $data->document);
                if (File::exists($docPath)) {
                    File::delete($docPath);
                }
            }

            $data->delete();

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
