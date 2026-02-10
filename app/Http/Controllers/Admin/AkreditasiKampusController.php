<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkreditasiKampus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AkreditasiKampusController extends Controller
{
    public function index()
    {
        return view('admin.akreditasi_kampus.index');
    }

    public function data(Request $request)
    {
        $data = AkreditasiKampus::query();
        return DataTables::of($data)
            ->editColumn('tanggal_sk', function ($row) {
                return $row->tanggal_sk ? $row->tanggal_sk->format('d/m/Y') : '-';
            })
            ->editColumn('kadaluarsa', function ($row) {
                return $row->kadaluarsa ? $row->kadaluarsa->format('d/m/Y') : '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'ya') {
                    return '<span class="badge bg-success">Aktif</span>';
                }
                return '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.akreditasi-kampus.edit', ['akreditasiKampus' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.akreditasi-kampus.delete') . '">
                                        ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function add()
    {
        return view('admin.akreditasi_kampus.add');
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'perguruan_tinggi' => 'nullable|string|max:255',
                'akreditasi'       => 'nullable|string|max:255',
                'tanggal_sk'       => 'nullable|date',
                'peringkat'        => 'nullable|string|max:255',
                'kadaluarsa'       => 'nullable|date',
                'status'           => 'required|in:tidak,ya',
            ]);

            $akreditasiKampus = new AkreditasiKampus();
            $akreditasiKampus->perguruan_tinggi = $request->perguruan_tinggi;
            $akreditasiKampus->akreditasi = $request->akreditasi;
            $akreditasiKampus->tanggal_sk = $request->tanggal_sk;
            $akreditasiKampus->peringkat = $request->peringkat;
            $akreditasiKampus->kadaluarsa = $request->kadaluarsa;
            $akreditasiKampus->status = $request->status;
            $akreditasiKampus->save();

            \DB::commit();
            return redirect()->route('admin.akreditasi-kampus.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AkreditasiKampus $akreditasiKampus)
    {
        return view('admin.akreditasi_kampus.edit', compact('akreditasiKampus'));
    }

    public function update(AkreditasiKampus $akreditasiKampus, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'perguruan_tinggi' => 'nullable|string|max:255',
                'akreditasi'       => 'nullable|string|max:255',
                'tanggal_sk'       => 'nullable|date',
                'peringkat'        => 'nullable|string|max:255',
                'kadaluarsa'       => 'nullable|date',
                'status'           => 'required|in:tidak,ya',
            ]);

            $akreditasiKampus->perguruan_tinggi = $request->perguruan_tinggi;
            $akreditasiKampus->akreditasi = $request->akreditasi;
            $akreditasiKampus->tanggal_sk = $request->tanggal_sk;
            $akreditasiKampus->peringkat = $request->peringkat;
            $akreditasiKampus->kadaluarsa = $request->kadaluarsa;
            $akreditasiKampus->status = $request->status;
            $akreditasiKampus->save();

            \DB::commit();
            return redirect()->route('admin.akreditasi-kampus.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = AkreditasiKampus::findOrFail($request->id);
            $data->delete();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }
}
