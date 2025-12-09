<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiAuditorDecree;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiAuditorDecreeController extends Controller
{
    private $uploadDir = 'storage/documents/ami-auditor-decree/';

    public function index()
    {
        return view('admin.ami-auditor-decree.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiAuditorDecree::with(['amiPeriod', 'prodi'])
            ->select('ami_auditor_decrees.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('number', 'LIKE', "%$search%");
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
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-decree.edit', ['amiAuditorDecree' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="' . $row->number . '">
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
        return view('admin.ami-auditor-decree.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'number' => 'nullable',
                'decree_date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $decree = new AmiAuditorDecree();
            $decree->ami_period_id = $request->ami_period_id;
            $decree->prodi_id = $request->prodi_id;
            $decree->number = $request->number;
            $decree->decree_date = $request->decree_date;
            $decree->start_date = $request->start_date;
            $decree->end_date = $request->end_date;
            $decree->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $decree->document = $fileName;
            }

            $decree->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-decree.index')->with('success', 'Berhasil menambahkan SK Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-decree.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-decree.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiAuditorDecree $amiAuditorDecree)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-auditor-decree.edit.index', compact('periods', 'prodis', 'amiAuditorDecree'));
    }

    public function update(AmiAuditorDecree $amiAuditorDecree, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'number' => 'nullable',
                'decree_date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $amiAuditorDecree->ami_period_id = $request->ami_period_id;
            $amiAuditorDecree->prodi_id = $request->prodi_id;
            $amiAuditorDecree->number = $request->number;
            $amiAuditorDecree->decree_date = $request->decree_date;
            $amiAuditorDecree->start_date = $request->start_date;
            $amiAuditorDecree->end_date = $request->end_date;
            $amiAuditorDecree->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                // Delete old document
                if ($amiAuditorDecree->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiAuditorDecree->document);
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
                $amiAuditorDecree->document = $fileName;
            }

            $amiAuditorDecree->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-decree.index')->with('success', 'Berhasil mengupdate SK Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-decree.edit', ['amiAuditorDecree' => $amiAuditorDecree->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-decree.edit', ['amiAuditorDecree' => $amiAuditorDecree->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = AmiAuditorDecree::findOrFail($request->id);

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
