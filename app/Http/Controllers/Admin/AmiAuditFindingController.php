<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiAuditFinding;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiAuditFindingController extends Controller
{
    private $uploadDir = 'storage/documents/ami-audit-finding/';

    public function index()
    {
        return view('admin.ami-audit-finding.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiAuditFinding::with(['amiPeriod', 'prodi'])->select('ami_audit_findings.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('lead_auditor', 'LIKE', "%$search%");
                    $query->orWhere('note', 'LIKE', "%$search%");
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
                return '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li><a class="dropdown-item" href="' . route('admin.ami-audit-finding.edit', ['amiAuditFinding' => $row->id]) . '">Edit</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Temuan Audit">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'status', 'document'])
            ->toJson();
    }

    public function add()
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-audit-finding.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'lead_auditor' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $finding = new AmiAuditFinding();
            $finding->ami_period_id = $request->ami_period_id;
            $finding->prodi_id = $request->prodi_id;
            $finding->lead_auditor = $request->lead_auditor;
            $finding->note = $request->note;
            $finding->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $finding->document = $fileName;
            }

            $finding->save();
            \DB::commit();
            return redirect()->route('admin.ami-audit-finding.index')->with('success', 'Berhasil menambahkan Temuan Audit');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-audit-finding.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiAuditFinding $amiAuditFinding)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-audit-finding.edit.index', compact('periods', 'prodis', 'amiAuditFinding'));
    }

    public function update(AmiAuditFinding $amiAuditFinding, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'lead_auditor' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $amiAuditFinding->ami_period_id = $request->ami_period_id;
            $amiAuditFinding->prodi_id = $request->prodi_id;
            $amiAuditFinding->lead_auditor = $request->lead_auditor;
            $amiAuditFinding->note = $request->note;
            $amiAuditFinding->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                if ($amiAuditFinding->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiAuditFinding->document);
                    if (File::exists($oldDocPath)) File::delete($oldDocPath);
                }
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiAuditFinding->document = $fileName;
            }

            $amiAuditFinding->save();
            \DB::commit();
            return redirect()->route('admin.ami-audit-finding.index')->with('success', 'Berhasil mengupdate Temuan Audit');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-audit-finding.edit', ['amiAuditFinding' => $amiAuditFinding->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $data = AmiAuditFinding::findOrFail($request->id);
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
