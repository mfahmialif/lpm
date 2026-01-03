<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class SkKompetensiController extends Controller
{
    public function index()
    {
        return view('admin.sk-kompetensi.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = SkKompetensi::select('*');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nomor_sk', 'LIKE', "%$search%");
                    $query->orWhere('tentang', 'LIKE', "%$search%");
                    $query->orWhere('ditetapkan_oleh', 'LIKE', "%$search%");
                });
            })
            ->editColumn('tanggal_sk', function ($row) {
                return $row->tanggal_sk ? $row->tanggal_sk->format('d-m-Y') : '-';
            })
            ->editColumn('file_sk', function ($row) {
                if ($row->file_sk) {
                    $url = asset('uploads/sk-kompetensi/' . $row->file_sk);
                    return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-label-primary"><i class="ti ti-file-download me-1"></i> Download</a>';
                }
                return '<span class="text-muted">Tidak ada file</span>';
            })
            ->addColumn('is_active', function ($row) {
                if ($row->is_active) {
                    return '<span class="badge bg-label-success">Aktif</span>';
                }
                return '<span class="badge bg-label-secondary">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-nomor_sk="' . $row->nomor_sk . '"
                                        data-tanggal_sk="' . optional($row->tanggal_sk)->format('Y-m-d') . '"
                                        data-tentang="' . $row->tentang . '"
                                        data-ditetapkan_oleh="' . $row->ditetapkan_oleh . '"
                                        data-is_active="' . ($row->is_active ? '1' : '0') . '"
                                        >Edit</button></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->nomor_sk . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'file_sk', 'is_active'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'nomor_sk' => 'required|unique:sk_kompetensi,nomor_sk',
                'tanggal_sk' => 'required|date',
                'tentang' => 'required',
                'ditetapkan_oleh' => 'required',
                'file_sk' => 'nullable|mimes:pdf|max:5120', // Max 5MB, PDF only
            ]);

            $data = $request->except('file_sk');
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('file_sk')) {
                $file = $request->file('file_sk');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/sk-kompetensi'), $filename);
                $data['file_sk'] = $filename;
            }

            SkKompetensi::create($data);

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan SK ' . $request->nomor_sk
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'nomor_sk' => 'required|unique:sk_kompetensi,nomor_sk,' . $request->id,
                'tanggal_sk' => 'required|date',
                'tentang' => 'required',
                'ditetapkan_oleh' => 'required',
                'file_sk' => 'nullable|mimes:pdf|max:5120',
            ]);

            $dataStore = SkKompetensi::findOrFail($request->id);

            $data = $request->except('file_sk');
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('file_sk')) {
                // Hapus file lama jika ada
                if ($dataStore->file_sk && File::exists(public_path('uploads/sk-kompetensi/' . $dataStore->file_sk))) {
                    File::delete(public_path('uploads/sk-kompetensi/' . $dataStore->file_sk));
                }

                $file = $request->file('file_sk');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/sk-kompetensi'), $filename);
                $data['file_sk'] = $filename;
            }

            $dataStore->update($data);

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success update data'
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = SkKompetensi::findOrFail($request->id);

            // Hapus file fisik jika ada
            if ($dataStore->file_sk && File::exists(public_path('uploads/sk-kompetensi/' . $dataStore->file_sk))) {
                File::delete(public_path('uploads/sk-kompetensi/' . $dataStore->file_sk));
            }

            $dataStore->delete();

            DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success delete data',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
