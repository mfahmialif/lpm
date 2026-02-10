<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaStrukturOrganisasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AnggotaStrukturOrganisasiController extends Controller
{
    public function index()
    {
        return view('admin.anggota_struktur_organisasi.index');
    }

    public function data(Request $request)
    {
        $data = AnggotaStrukturOrganisasi::query()->orderBy('nama', 'asc');
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.anggota-struktur-organisasi.edit', ['anggotaStrukturOrganisasi' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.anggota-struktur-organisasi.delete') . '">
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
        return view('admin.anggota_struktur_organisasi.add');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);

            AnggotaStrukturOrganisasi::create([
                'nama' => $request->nama,
            ]);

            return redirect()->route('admin.anggota-struktur-organisasi.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AnggotaStrukturOrganisasi $anggotaStrukturOrganisasi)
    {
        return view('admin.anggota_struktur_organisasi.edit', compact('anggotaStrukturOrganisasi'));
    }

    public function update(AnggotaStrukturOrganisasi $anggotaStrukturOrganisasi, Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);

            $anggotaStrukturOrganisasi->update([
                'nama' => $request->nama,
            ]);

            return redirect()->route('admin.anggota-struktur-organisasi.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = AnggotaStrukturOrganisasi::findOrFail($request->id);
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
