<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiFinalResult;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiFinalResultController extends Controller
{
    private $uploadDir = 'storage/documents/ami-final-result/';

    public function index()
    {
        return view('admin.ami-final-result.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiFinalResult::with(['amiPeriod', 'prodi'])->select('ami_final_results.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('end_score_spme', 'LIKE', "%$search%");
                    $query->orWhere('score_ikt', 'LIKE', "%$search%");
                    $query->orWhere('end_score_ami', 'LIKE', "%$search%");
                    $query->orWhere('rank_ami', 'LIKE', "%$search%");
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
            ->editColumn('accreditation_status', function ($row) {
                $colors = ['A' => 'success', 'B' => 'info', 'C' => 'warning', 'Not Accredited' => 'secondary'];
                $color = $colors[$row->accreditation_status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . $row->accreditation_status . '</span>';
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
                            <li><a class="dropdown-item" href="' . route('admin.ami-final-result.edit', ['amiFinalResult' => $row->id]) . '">Edit</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Hasil Akhir">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'accreditation_status', 'document'])
            ->toJson();
    }

    public function add()
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-final-result.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'end_score_spme' => 'nullable',
                'score_ikt' => 'nullable',
                'end_score_ami' => 'nullable',
                'rank_ami' => 'nullable',
                'accreditation_status' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
            ]);

            $result = new AmiFinalResult();
            $result->ami_period_id = $request->ami_period_id;
            $result->prodi_id = $request->prodi_id;
            $result->end_score_spme = $request->end_score_spme;
            $result->score_ikt = $request->score_ikt;
            $result->end_score_ami = $request->end_score_ami;
            $result->rank_ami = $request->rank_ami;
            $result->accreditation_status = $request->accreditation_status ?? 'Not Accredited';
            $result->note = $request->note;

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
            return redirect()->route('admin.ami-final-result.index')->with('success', 'Berhasil menambahkan Hasil Akhir');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-final-result.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiFinalResult $amiFinalResult)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-final-result.edit.index', compact('periods', 'prodis', 'amiFinalResult'));
    }

    public function update(AmiFinalResult $amiFinalResult, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'end_score_spme' => 'nullable',
                'score_ikt' => 'nullable',
                'end_score_ami' => 'nullable',
                'rank_ami' => 'nullable',
                'accreditation_status' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
            ]);

            $amiFinalResult->ami_period_id = $request->ami_period_id;
            $amiFinalResult->prodi_id = $request->prodi_id;
            $amiFinalResult->end_score_spme = $request->end_score_spme;
            $amiFinalResult->score_ikt = $request->score_ikt;
            $amiFinalResult->end_score_ami = $request->end_score_ami;
            $amiFinalResult->rank_ami = $request->rank_ami;
            $amiFinalResult->accreditation_status = $request->accreditation_status ?? 'Not Accredited';
            $amiFinalResult->note = $request->note;

            if ($request->hasFile('document')) {
                if ($amiFinalResult->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiFinalResult->document);
                    if (File::exists($oldDocPath)) File::delete($oldDocPath);
                }
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiFinalResult->document = $fileName;
            }

            $amiFinalResult->save();
            \DB::commit();
            return redirect()->route('admin.ami-final-result.index')->with('success', 'Berhasil mengupdate Hasil Akhir');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-final-result.edit', ['amiFinalResult' => $amiFinalResult->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $data = AmiFinalResult::findOrFail($request->id);

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
