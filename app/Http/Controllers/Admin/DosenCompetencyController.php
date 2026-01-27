<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\DosenCompetency;
use App\Models\PeriodeAkademik;
use App\Models\Prodi;
use App\Models\ProdiCompetency;
use App\Models\SkKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Imports\DosenCompetencyImport;
use App\Exports\DosenCompetencyTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class DosenCompetencyController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.dosen-competency.index');
    }

    public function add()
    {
        $dosens = Dosen::all();
        $prodis = Prodi::all();
        $periodes = PeriodeAkademik::where('is_active', 1)->get();
        if ($periodes->isEmpty()) $periodes = PeriodeAkademik::orderBy('id', 'desc')->get();

        $sks = SkKompetensi::where('is_active', true)->orderBy('id', 'desc')->get();
        if ($sks->isEmpty()) {
            $sks = SkKompetensi::orderBy('id', 'desc')->get();
        }

        return view('admin.dosen-competency.create', compact('dosens', 'prodis', 'periodes', 'sks'));
    }

    public function edit($id)
    {
        $data = DosenCompetency::with('prodiCompetency')->findOrFail($id);
        $dosens = Dosen::all();
        $prodis = Prodi::all();
        $periodes = PeriodeAkademik::orderBy('id', 'desc')->get();
        $sks = SkKompetensi::orderBy('id', 'desc')->get();

        return view('admin.dosen-competency.edit', compact('data', 'dosens', 'prodis', 'periodes', 'sks'));
    }

    public function data(Request $request)
    {
        $data = DosenCompetency::with([
            'dosen',
            'prodiCompetency.prodi',
            'prodiCompetency.competency',
            'periodeAkademik',
            'skKompetensi'
        ])->select('dosen_competencies.*');

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->whereHas('dosen', function ($q) use ($search) {
                        $q->where('nama', 'LIKE', "%$search%")
                            ->orWhere('nidn', 'LIKE', "%$search%");
                    })->orWhereHas('prodiCompetency', function ($pi) use ($search) {
                        $pi->whereHas('competency', function ($q) use ($search) {
                            $q->where('nama', 'LIKE', "%$search%");
                        })->orWhereHas('prodi', function ($q) use ($search) {
                            $q->where('nama', 'LIKE', "%$search%");
                        });
                    });
                }
            })
            ->editColumn('dosen', function ($row) {
                return '<strong>' . $row->dosen->nama . '</strong><br><small class="text-muted">' . $row->dosen->nidn . '</small>';
            })
            ->addColumn('kompetensi', function ($row) {
                if ($row->prodiCompetency && $row->prodiCompetency->competency) {
                    return '<strong>' . $row->prodiCompetency->competency->nama . '</strong><br><small class="text-muted">' . $row->prodiCompetency->prodi->nama . '</small>';
                }
                return '<span class="text-danger">Data Invalid</span>';
            })
            ->editColumn('periode', function ($row) {
                return $row->periodeAkademik->nama_periode;
            })
            ->editColumn('status', function ($row) {
                $statusClass = [
                    'MENUNGGU' => 'warning',
                    'AKTIF' => 'success',
                    'DITOLAK' => 'danger',
                    'KADALUARSA' => 'secondary'
                ];
                $cls = $statusClass[$row->status] ?? 'primary';
                return '<span class="badge bg-label-' . $cls . '">' . $row->status . '</span>';
            })
            ->editColumn('sk', function ($row) {
                return $row->skKompetensi->nomor_sk;
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                 <a class="dropdown-item" href="' . route('admin.dosen-competency.edit', $row->id) . '">
                                    <i class="ti ti-pencil me-1"></i> Edit
                                </a>
                                <form action="' . route('admin.dosen-competency.delete') . '" method="POST" class="d-inline form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Kompetensi ' . $row->dosen->nama . '">
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ti ti-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'dosen', 'kompetensi', 'status'])
            ->toJson();
    }

    public function getCompetenciesByProdi($prodiId)
    {
        // Ambil kompetensi yang tersedia di prodi ini
        // Return list of ProdiCompetency ID beserta nama kompetensinya
        $list = ProdiCompetency::where('prodi_id', $prodiId)
            ->with('competency')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id, // Ini adalah prodi_competency_id yang akan disimpan
                    'nama_competency' => $item->competency->nama,
                    'kode_competency' => $item->competency->kode
                ];
            });

        return response()->json($list);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:mst_dosen,id',
            'prodi_id' => 'required',
            'prodi_competency_id' => 'required|exists:prodi_competencies,id',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'sk_kompetensi_id' => 'required|exists:sk_kompetensi,id',
            'status' => 'nullable|in:MENUNGGU,AKTIF,DITOLAK,KADALUARSA',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        // Cek duplikasi
        $exists = DosenCompetency::where('prodi_competency_id', $request->prodi_competency_id)
            ->where('periode_akademik_id', $request->periode_akademik_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['prodi_competency_id' => 'Data kompetensi ini sudah ada di periode akademik yang dipilih.'])->withInput();
        }

        DosenCompetency::create($request->all());

        return redirect()->route('admin.dosen-competency.index')->with('success', 'Berhasil menambahkan kompetensi dosen');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dosen_id' => 'required|exists:mst_dosen,id',
            'prodi_competency_id' => 'required|exists:prodi_competencies,id',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'sk_kompetensi_id' => 'required|exists:sk_kompetensi,id',
            'status' => 'nullable|in:MENUNGGU,AKTIF,DITOLAK,KADALUARSA',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $dataStore = DosenCompetency::findOrFail($id);

        // Cek duplikasi jika berubah
        if (
            $dataStore->prodi_competency_id != $request->prodi_competency_id ||
            $dataStore->periode_akademik_id != $request->periode_akademik_id
        ) {
            $exists = DosenCompetency::where('prodi_competency_id', $request->prodi_competency_id)
                ->where('periode_akademik_id', $request->periode_akademik_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return back()->withErrors(['prodi_competency_id' => 'Data kompetensi ini sudah ada di periode akademik yang dipilih.'])->withInput();
            }
        }

        $dataStore->update($request->all());

        return redirect()->route('admin.dosen-competency.index')->with('success', 'Berhasil memperbarui data kompetensi dosen');
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = DosenCompetency::findOrFail($request->id);
            $dataStore->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Success delete data',
                'request' => $request->all(),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new DosenCompetencyImport, $request->file('file'));
            
            // Get import results from session
            $importedCount = session('imported_count', 0);
            $errors = session('import_errors', []);
            
            $message = "Berhasil mengimpor {$importedCount} data kompetensi dosen.";
            
            if (!empty($errors)) {
                $message .= " " . count($errors) . " data gagal diimpor.";
                return redirect()->route('admin.dosen-competency.index')
                    ->with('success', $message)
                    ->with('import_errors', $errors);
            }
            
            return redirect()->route('admin.dosen-competency.index')->with('success', $message);
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal mengimpor data: ' . $th->getMessage());
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new DosenCompetencyTemplateExport, 'template_kompetensi_dosen.xlsx');
    }
}
