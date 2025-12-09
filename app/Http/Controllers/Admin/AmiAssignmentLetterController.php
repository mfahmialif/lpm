<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiAssignmentLetter;
use App\Models\AmiPeriod;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AmiAssignmentLetterController extends Controller
{
    private $uploadDir = 'storage/documents/ami-assignment-letter/';

    public function index()
    {
        return view('admin.ami-assignment-letter.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiAssignmentLetter::with(['amiPeriod', 'prodi'])
            ->select('ami_assignment_letters.*');

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
                                <a class="dropdown-item" href="' . route('admin.ami-assignment-letter.edit', ['amiAssignmentLetter' => $row->id]) . '">Edit</a>
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
        return view('admin.ami-assignment-letter.add.index', compact('periods', 'prodis'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'number' => 'nullable',
                'assignment_date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $letter = new AmiAssignmentLetter();
            $letter->ami_period_id = $request->ami_period_id;
            $letter->prodi_id = $request->prodi_id;
            $letter->number = $request->number;
            $letter->assignment_date = $request->assignment_date;
            $letter->start_date = $request->start_date;
            $letter->end_date = $request->end_date;
            $letter->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $letter->document = $fileName;
            }

            $letter->save();

            \DB::commit();
            return redirect()->route('admin.ami-assignment-letter.index')->with('success', 'Berhasil menambahkan Surat Tugas AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-assignment-letter.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-assignment-letter.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiAssignmentLetter $amiAssignmentLetter)
    {
        $periods = AmiPeriod::all();
        $prodis = Prodi::all();
        return view('admin.ami-assignment-letter.edit.index', compact('periods', 'prodis', 'amiAssignmentLetter'));
    }

    public function update(AmiAssignmentLetter $amiAssignmentLetter, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_id' => 'required',
                'number' => 'nullable',
                'assignment_date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $amiAssignmentLetter->ami_period_id = $request->ami_period_id;
            $amiAssignmentLetter->prodi_id = $request->prodi_id;
            $amiAssignmentLetter->number = $request->number;
            $amiAssignmentLetter->assignment_date = $request->assignment_date;
            $amiAssignmentLetter->start_date = $request->start_date;
            $amiAssignmentLetter->end_date = $request->end_date;
            $amiAssignmentLetter->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                // Delete old document
                if ($amiAssignmentLetter->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiAssignmentLetter->document);
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
                $amiAssignmentLetter->document = $fileName;
            }

            $amiAssignmentLetter->save();

            \DB::commit();
            return redirect()->route('admin.ami-assignment-letter.index')->with('success', 'Berhasil mengupdate Surat Tugas AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-assignment-letter.edit', ['amiAssignmentLetter' => $amiAssignmentLetter->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-assignment-letter.edit', ['amiAssignmentLetter' => $amiAssignmentLetter->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = AmiAssignmentLetter::findOrFail($request->id);

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
