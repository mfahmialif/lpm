<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiTarget;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiTargetController extends Controller
{
    private $uploadDir = 'storage/documents/ami-target/';

    public function index()
    {
        return view('admin.ami-target.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiTarget::select(
            'ami_targets.*',
            'ami_periods.year as ami_period',
            'prodis.nama as prodi_name'
        )
            ->leftJoin('ami_periods', 'ami_targets.ami_period_id', '=', 'ami_periods.id')
            ->leftJoin('prodis', 'ami_targets.prodi_id', '=', 'prodis.id');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ami_targets.evaluations', 'LIKE', "%$search%");
                    $query->orWhere('ami_targets.assessment_guide', 'LIKE', "%$search%");
                    $query->orWhere('ami_periods.year', 'LIKE', "%$search%");
                    $query->orWhere('prodis.nama', 'LIKE', "%$search%");
                });
            })
            ->editColumn('ami_period', function ($row) {
                return $row->ami_period ?: '-';
            })
            ->editColumn('prodi_name', function ($row) {
                return $row->prodi_name ?: '-';
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
                                <a class="dropdown-item" href="' . route('admin.ami-target.edit', ['amiTarget' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Target ' . ($row->prodi ? $row->prodi->nama : '') . '">
                                    <button type="submit" class="dropdown-item text-danger">
                                        Delete
                                    </button>
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
        return view('admin.ami-target.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'evaluations' => 'nullable',
                'assessment_guide' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $target = new AmiTarget();
            $target->ami_period_id = $request->ami_period_id;
            $target->prodi_id = $request->prodi_id;
            $target->evaluations = $request->evaluations;
            $target->assessment_guide = $request->assessment_guide;
            $target->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $target->document = $fileName;
            }

            $target->save();

            \DB::commit();
            return redirect()->route('admin.ami-target.index')->with('success', 'Berhasil menambahkan target AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-target.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-target.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiTarget $amiTarget)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-target.edit.index', compact('periods', 'prodis', 'amiTarget'));
    }

    public function update(AmiTarget $amiTarget, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'evaluations' => 'nullable',
                'assessment_guide' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $amiTarget->ami_period_id = $request->ami_period_id;
            $amiTarget->prodi_id = $request->prodi_id;
            $amiTarget->evaluations = $request->evaluations;
            $amiTarget->assessment_guide = $request->assessment_guide;
            $amiTarget->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                // Delete old document
                if ($amiTarget->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiTarget->document);
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
                $amiTarget->document = $fileName;
            }

            $amiTarget->save();

            \DB::commit();
            return redirect()->route('admin.ami-target.index')->with('success', 'Berhasil mengupdate target AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-target.edit', ['amiTarget' => $amiTarget->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-target.edit', ['amiTarget' => $amiTarget->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = AmiTarget::findOrFail($request->id);

            // Delete document if exists
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
