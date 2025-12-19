<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiOfficialReport;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiOfficialReportController extends Controller
{
    private $uploadDir = 'storage/documents/ami-official-report/';

    public function index()
    {
        return view('admin.ami-official-report.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiOfficialReport::select(
            'ami_official_reports.*',
            'ami_periods.year as ami_period',
            'prodis.nama as prodi_name'
        )
            ->leftJoin('ami_periods', 'ami_official_reports.ami_period_id', '=', 'ami_periods.id')
            ->leftJoin('prodis', 'ami_official_reports.prodi_id', '=', 'prodis.id');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ami_official_reports.effidence', 'LIKE', "%$search%");
                    $query->orWhere('ami_official_reports.note', 'LIKE', "%$search%");
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
                            <li><a class="dropdown-item" href="' . route('admin.ami-official-report.edit', ['amiOfficialReport' => $row->id]) . '">Edit</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Laporan Resmi">
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
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-official-report.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'effidence' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
            ]);

            $report = new AmiOfficialReport();
            $report->ami_period_id = $request->ami_period_id;
            $report->prodi_id = $request->prodi_id;
            $report->effidence = $request->effidence;
            $report->note = $request->note;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $report->document = $fileName;
            }

            $report->save();
            \DB::commit();
            return redirect()->route('admin.ami-official-report.index')->with('success', 'Berhasil menambahkan Laporan Resmi');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-official-report.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiOfficialReport $amiOfficialReport)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-official-report.edit.index', compact('periods', 'prodis', 'amiOfficialReport'));
    }

    public function update(AmiOfficialReport $amiOfficialReport, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'effidence' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
            ]);

            $amiOfficialReport->ami_period_id = $request->ami_period_id;
            $amiOfficialReport->prodi_id = $request->prodi_id;
            $amiOfficialReport->effidence = $request->effidence;
            $amiOfficialReport->note = $request->note;

            if ($request->hasFile('document')) {
                if ($amiOfficialReport->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiOfficialReport->document);
                    if (File::exists($oldDocPath)) File::delete($oldDocPath);
                }
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiOfficialReport->document = $fileName;
            }

            $amiOfficialReport->save();
            \DB::commit();
            return redirect()->route('admin.ami-official-report.index')->with('success', 'Berhasil mengupdate Laporan Resmi');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-official-report.edit', ['amiOfficialReport' => $amiOfficialReport->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $data = AmiOfficialReport::findOrFail($request->id);

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
