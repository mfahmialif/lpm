<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SambutanKetua;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Services\ImageHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SambutanKetuaController extends Controller
{
    private $uploadDir = 'storage/image-sambutan-ketua/';

    public function index()
    {
        return view('admin.sambutan_ketua.index');
    }

    public function data(Request $request)
    {
        $data = SambutanKetua::query();
        return DataTables::of($data)
            ->editColumn('foto', function ($row) {
                if ($row->foto) {
                    return '<img src="' . asset($this->uploadDir . $row->foto) . '" width="50" height="50" style="object-fit:cover" class="rounded-circle">';
                }
                return '-';
            })
            ->editColumn('sambutan', function ($row) {
                return Str::limit(strip_tags($row->sambutan), 50);
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.sambutan-ketua.edit', ['sambutanKetua' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.sambutan-ketua.delete') . '">
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
            ->rawColumns(['action', 'foto'])
            ->toJson();
    }

    public function add()
    {
        return view('admin.sambutan_ketua.add');
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'nama_ketua' => 'required',
                'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'sambutan'   => 'required',
            ]);

            $sambutanKetua = new SambutanKetua();
            $sambutanKetua->nama_ketua = $request->nama_ketua;
            $sambutanKetua->sambutan = $request->sambutan;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $compressedUrl = ImageHelper::compressUploadedImage($file, $this->uploadDir);
                $sambutanKetua->foto = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $sambutanKetua->save();
            \DB::commit();
            return redirect()->route('admin.sambutan-ketua.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(SambutanKetua $sambutanKetua)
    {
        return view('admin.sambutan_ketua.edit', compact('sambutanKetua'));
    }

    public function update(SambutanKetua $sambutanKetua, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'nama_ketua' => 'required',
                'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'sambutan'   => 'required',
            ]);

            $sambutanKetua->nama_ketua = $request->nama_ketua;
            $sambutanKetua->sambutan = $request->sambutan;

            if ($request->hasFile('foto')) {
                // Delete old photo
                if ($sambutanKetua->foto) {
                    $oldPath = public_path($this->uploadDir . $sambutanKetua->foto);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $file = $request->file('foto');
                $compressedUrl = ImageHelper::compressUploadedImage($file, $this->uploadDir);
                $sambutanKetua->foto = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $sambutanKetua->save();
            \DB::commit();
            return redirect()->route('admin.sambutan-ketua.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = SambutanKetua::findOrFail($request->id);
            if ($data->foto) {
                $oldPath = public_path($this->uploadDir . $data->foto);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
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
