<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Models\PeriodeLpm;
use App\Models\AnggotaStrukturOrganisasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        return view('admin.struktur_organisasi.index');
    }

    public function data(Request $request)
    {
        $data = StrukturOrganisasi::with('periodeLpm')->select('struktur_organisasis.*');
        return DataTables::of($data)
            ->addColumn('periode', function ($row) {
                if ($row->periodeLpm) {
                    return $row->periodeLpm->dari->format('Y') . ' - ' . $row->periodeLpm->sampai->format('Y');
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
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.struktur-organisasi.edit', ['strukturOrganisasi' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.struktur-organisasi.delete') . '">
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
            ->rawColumns(['action'])
            ->toJson();
    }

    public function add()
    {
        $periodes = PeriodeLpm::orderBy('dari', 'desc')->get();
        $anggotaList = AnggotaStrukturOrganisasi::orderBy('nama', 'asc')->get();
        return view('admin.struktur_organisasi.add', compact('periodes', 'anggotaList'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'periode_lpm_id' => 'required|exists:periode_lpms,id',
            ]);

            $data = $request->except('anggota_ids');

            // Store anggota IDs as JSON
            if ($request->has('anggota_ids')) {
                $data['anggota'] = json_encode(array_map('intval', $request->anggota_ids));
            }

            StrukturOrganisasi::create($data);

            return redirect()->route('admin.struktur-organisasi.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(StrukturOrganisasi $strukturOrganisasi)
    {
        $periodes = PeriodeLpm::orderBy('dari', 'desc')->get();
        $anggotaList = AnggotaStrukturOrganisasi::orderBy('nama', 'asc')->get();
        return view('admin.struktur_organisasi.edit', compact('strukturOrganisasi', 'periodes', 'anggotaList'));
    }

    public function update(StrukturOrganisasi $strukturOrganisasi, Request $request)
    {
        try {
            $request->validate([
                'periode_lpm_id' => 'required|exists:periode_lpms,id',
            ]);

            $data = $request->except('anggota_ids');

            // Store anggota IDs as JSON
            if ($request->has('anggota_ids')) {
                $data['anggota'] = json_encode(array_map('intval', $request->anggota_ids));
            } else {
                $data['anggota'] = null;
            }

            $strukturOrganisasi->update($data);

            return redirect()->route('admin.struktur-organisasi.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = StrukturOrganisasi::findOrFail($request->id);
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
