<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProdiCompetencyController extends Controller
{
    public function index()
    {
        $prodis = Prodi::all();
        $competencies = Competency::all();
        return view('admin.prodi-competency.index', compact('prodis', 'competencies'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = Prodi::withCount('competencies');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('fakultas', 'LIKE', "%$search%");
                });
            })
            ->editColumn('competencies_count', function ($row) {
                return '<span class="badge bg-label-primary">' . $row->competencies_count . ' Kompetensi</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                        <div class="d-inline-block">
                             <a href="' . route('admin.prodi-competency.edit', $row->id) . '" class="btn btn-sm btn-icon"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Kelola Kompetensi">
                                <i class="ti ti-edit"></i>
                            </a>
                        </div>';
            })
            ->rawColumns(['action', 'competencies_count'])
            ->toJson();
    }

    public function getProdiCompetencies($id)
    {
        $prodi = Prodi::with('competencies')->findOrFail($id);
        return response()->json($prodi->competencies->pluck('id'));
    }

    public function edit($id)
    {
        $prodis = Prodi::orderBy('nama')->get(); // Ambil semua prodi untuk dropdown
        $prodi = Prodi::findOrFail($id);
        // Load existing competencies IDs for checking checkboxes
        $existingCompetencies = $prodi->competencies->pluck('id')->toArray();
        $competencies = Competency::with('prodis')->get();

        return view('admin.prodi-competency.edit', compact('prodis', 'prodi', 'competencies', 'existingCompetencies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'competency_ids' => 'array',
            'competency_ids.*' => 'exists:competencies,id',
        ]);

        try {
            DB::beginTransaction();
            $prodi = Prodi::findOrFail($id);
            $prodi->competencies()->sync($request->competency_ids ?? []);
            DB::commit();

            return redirect()->route('admin.prodi-competency.index')
                ->with('success', 'Berhasil memperbarui kompetensi untuk ' . $prodi->nama);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
