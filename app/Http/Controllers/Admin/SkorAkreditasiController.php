<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkorAkreditasi;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SkorAkreditasiController extends Controller
{
    public function index()
    {
        return view('admin.skor_akreditasi.index');
    }

    public function data(Request $request)
    {
        $data = SkorAkreditasi::with('prodi')->select('skor_akreditasis.*');
        return DataTables::of($data)
            ->addColumn('prodi_nama', function ($row) {
                return $row->prodi ? $row->prodi->nama : '-';
            })
            ->addColumn('tgl_kadaluarsa_formatted', function ($row) {
                return $row->tgl_kadaluarsa ? $row->tgl_kadaluarsa->format('d M Y') : '-';
            })
            ->addColumn('status_badge', function ($row) {
                $badge = $row->status == 'masih berlaku' ? 'bg-label-success' : 'bg-label-danger';
                return '<span class="badge ' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('link_drive_button', function ($row) {
                if ($row->link_drive) {
                    return '<a href="' . $row->link_drive . '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ti ti-external-link"></i> Lihat</a>';
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
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.skor-akreditasi.edit', ['skorAkreditasi' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.skor-akreditasi.delete') . '">
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
            ->rawColumns(['status_badge', 'link_drive_button', 'action'])
            ->toJson();
    }

    public function add()
    {
        $prodis = Prodi::orderBy('nama', 'asc')->get();
        return view('admin.skor_akreditasi.add', compact('prodis'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:masih berlaku,kadaluarsa',
            ]);

            SkorAkreditasi::create($request->only([
                'perguruan_tinggi',
                'prodi_id',
                'strata',
                'wilayah',
                'no_sk',
                'tgl_kadaluarsa',
                'peringkat',
                'tahun_sk',
                'status',
                'link_drive',
            ]));

            return redirect()->route('admin.skor-akreditasi.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(SkorAkreditasi $skorAkreditasi)
    {
        $prodis = Prodi::orderBy('nama', 'asc')->get();
        return view('admin.skor_akreditasi.edit', compact('skorAkreditasi', 'prodis'));
    }

    public function update(SkorAkreditasi $skorAkreditasi, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:masih berlaku,kadaluarsa',
            ]);

            $skorAkreditasi->update($request->only([
                'perguruan_tinggi',
                'prodi_id',
                'strata',
                'wilayah',
                'no_sk',
                'tgl_kadaluarsa',
                'peringkat',
                'tahun_sk',
                'status',
                'link_drive',
            ]));

            return redirect()->route('admin.skor-akreditasi.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = SkorAkreditasi::findOrFail($request->id);
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
