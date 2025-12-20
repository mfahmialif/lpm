<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiAuditorAssessment;
use App\Models\AmiPeriod;
use App\Models\ProdiUnit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiAuditorAssessmentController extends Controller
{
    private $uploadDir = 'storage/documents/ami-auditor-assessment/';

    public function index()
    {
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-auditor-assessment.index', compact('prodiUnits'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiAuditorAssessment::select(
            'ami_auditor_assessments.*',
            'ami_periods.year as ami_period',
            'prodi_units.nama as prodi_unit_name'
        )
            ->leftJoin('ami_periods', 'ami_auditor_assessments.ami_period_id', '=', 'ami_periods.id')
            ->leftJoin('prodi_units', 'ami_auditor_assessments.prodi_unit_id', '=', 'prodi_units.id');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ami_auditor_assessments.assessment_guide', 'LIKE', "%$search%");
                    $query->orWhere('ami_auditor_assessments.auditee_name', 'LIKE', "%$search%");
                    $query->orWhere('ami_auditor_assessments.note', 'LIKE', "%$search%");
                    $query->orWhere('ami_periods.year', 'LIKE', "%$search%");
                    $query->orWhere('prodi_units.nama', 'LIKE', "%$search%");
                });
                $query->when(request('prodi_unit_id') != '*', function ($query) {
                    $query->where('ami_auditor_assessments.prodi_unit_id', request('prodi_unit_id'));
                });
            })
            ->editColumn('ami_period', function ($row) {
                return $row->ami_period ?: '-';
            })
            ->editColumn('prodi_unit_name', function ($row) {
                return $row->prodi_unit_name ?: '-';
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
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Asesmen Auditor ' . ($row->prodi_name ?: '') . '">
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
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-auditor-assessment.add.index', compact('periods', 'prodiUnits'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_unit_id' => 'required',
                'assessment_guide' => 'nullable',
                'auditee_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $assessment = new AmiAuditorAssessment();
            $assessment->ami_period_id = $request->ami_period_id;
            $assessment->prodi_unit_id = $request->prodi_unit_id;
            $assessment->assessment_guide = $request->assessment_guide;
            $assessment->auditee_name = $request->auditee_name;
            $assessment->note = $request->note;
            $assessment->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $assessment->document = $fileName;
            }

            $assessment->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-assessment.index')->with('success', 'Berhasil menambahkan Asesmen Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-assessment.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-assessment.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $periods = AmiPeriod::all();
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-auditor-assessment.edit.index', compact('periods', 'prodiUnits', 'amiAuditorAssessment'));
    }

    public function update(AmiAuditorAssessment $amiAuditorAssessment, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_unit_id' => 'required',
                'assessment_guide' => 'nullable',
                'auditee_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $amiAuditorAssessment->ami_period_id = $request->ami_period_id;
            $amiAuditorAssessment->prodi_unit_id = $request->prodi_unit_id;
            $amiAuditorAssessment->assessment_guide = $request->assessment_guide;
            $amiAuditorAssessment->auditee_name = $request->auditee_name;
            $amiAuditorAssessment->note = $request->note;
            $amiAuditorAssessment->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                if ($amiAuditorAssessment->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiAuditorAssessment->document);
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
                $amiAuditorAssessment->document = $fileName;
            }

            $amiAuditorAssessment->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-assessment.index')->with('success', 'Berhasil mengupdate Asesmen Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $amiAuditorAssessment->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $amiAuditorAssessment->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate(['id' => 'required']);

            $data = AmiAuditorAssessment::findOrFail($request->id);

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
