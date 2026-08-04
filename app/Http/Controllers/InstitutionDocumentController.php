<?php

namespace App\Http\Controllers;

use App\Models\DocumentKeputusanRektor;
use App\Models\DocumentSpmi;
use App\Models\DocumentSiklusPpepp;
use App\Models\DocumentStatutaUiiDalwa;
use App\Models\DocumentRenstraUiiDalwa;
use App\Models\DocumentRip;
use App\Models\DocumentRenopUiiDalwa;
use App\Models\DocumentSotkUiiDalwa;
use App\Models\DocumentKurikulumProdi;
use App\Models\DocumentLaporanBanchmarking;
use App\Models\DocumentLaporanEvaluasiPpepp;
use App\Models\DocumentPedoman;
use App\Models\SkPendirianProdi;
use App\Models\UnitDokument;
use App\Models\Prodi as ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Services\Jurnal;
use App\Http\Services\Buku;
use App\Http\Services\ProdiKaryaDosen;
use App\Http\Services\Prosiding;
use App\Http\Services\Rps;
use App\Http\Services\Prodi;

class InstitutionDocumentController extends Controller
{
    public function index()
    {
        $units = UnitDokument::whereNotIn('jenis', ['Institusi', 'Prodi', 'Fakultas'])
            ->orderByRaw('COALESCE(posisi, 999999) ASC')
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil data prodi dari external API
        $prodis = ProdiKaryaDosen::all() ?? [];
        $prodiRps = Prodi::getData() ?? [];

        $unit_ppepp = UnitDokument::where('jenis', 'prodi')->orderBy('nama', 'asc')->get();
        $unit_renstra = UnitDokument::where('jenis', 'Fakultas')->orderBy('nama', 'asc')->get();
        $unit_renstra = UnitDokument::where('jenis', 'Fakultas')->orderBy('nama', 'asc')->get();
        $unit_renop = UnitDokument::whereIn('jenis', ['Fakultas', 'Universitas', 'Pasca Sarjana'])->orderBy('nama', 'asc')->get();
        $unit_rip = UnitDokument::whereIn('jenis', ['Fakultas', 'Universitas', 'Pasca Sarjana'])->orderBy('nama', 'asc')->get();
        // Laporan Benchmarking uses same unit types as Renop
        $unit_benchmarking = $unit_renop;
        $unit_pedoman = UnitDokument::whereIn('jenis', ['Fakultas', 'Universitas', 'Pasca Sarjana'])->orderBy('nama', 'asc')->get();
        $list_prodi = ProdiModel::orderBy('nama', 'asc')->get();

        return view('institution-document.index', compact('units', 'prodis', 'prodiRps', 'unit_ppepp', 'list_prodi', 'unit_renstra', 'unit_rip', 'unit_renop', 'unit_benchmarking', 'unit_pedoman'));
    }

    // Helper function untuk generate file button
    private function generateFileButton($path)
    {
        if ($path) {
            $fileUrl = asset(str_replace('\\', '/', $path));

            return '<div class="d-flex gap-1">
                <a href="' . $fileUrl . '" target="_blank" class="btn btn-sm btn-info" title="Preview">
                    <i class="ti ti-eye"></i>
                </a>
                <a href="' . $fileUrl . '" download target="_blank" class="btn btn-sm btn-primary" title="Unduh">
                    <i class="ti ti-download"></i>
                </a>
            </div>';
        }
        return '<span class="badge bg-secondary">-</span>';
    }

    // API untuk DataTables - Keputusan Rektor
    public function dataKeputusanRektor(Request $request)
    {
        $query = DocumentKeputusanRektor::leftJoin('units_dokument', 'document_keputusan_rektor.unit_dokument_id', '=', 'units_dokument.id')
            ->select([
                'document_keputusan_rektor.*',
                'units_dokument.nama as unit_nama'
            ])
            ->orderBy('document_keputusan_rektor.created_at', 'desc');

        // Filter by unit_dokument_id if provided
        if ($request->has('unit_dokument_id') && $request->unit_dokument_id != '') {
            $query->where('document_keputusan_rektor.unit_dokument_id', $request->unit_dokument_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit_nama', function ($row) {
                return $row->unit_nama ? $row->unit_nama : '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['unit_nama', 'file'])
            ->make(true);
    }

    // API untuk DataTables - SK Pendirian Prodi
    public function dataSkPendirianProdi()
    {
        $data = SkPendirianProdi::orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'acc') {
                    return '<span class="badge bg-success">ACC</span>';
                } elseif ($row->status === 'tolak') {
                    return '<span class="badge bg-danger">Tolak</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['status_badge', 'file'])
            ->make(true);
    }

    // API untuk DataTables - SPMI
    public function dataSpmi()
    {
        $data = DocumentSpmi::orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Siklus PPEPP
    public function dataSiklusPpepp(Request $request)
    {
        $query = DocumentSiklusPpepp::query()->orderBy('created_at', 'desc');

        // Filter by unit_dokumen_id if provided
        if ($request->has('unit_dokumen_id') && $request->unit_dokumen_id != '') {
            $query->where('unit_dokumen_id', $request->unit_dokumen_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Statuta
    public function dataStatuta()
    {
        $data = DocumentStatutaUiiDalwa::orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Renstra
    public function dataRenstra(Request $request)
    {
        $query = DocumentRenstraUiiDalwa::with('unit')->orderBy('created_at', 'desc');

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Renop
    public function dataRenop(Request $request)
    {
        $query = DocumentRenopUiiDalwa::with('unit')->orderBy('created_at', 'desc');

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - RIP (Rencana Induk Pengembangan)
    public function dataRip(Request $request)
    {
        $query = DocumentRip::with('unit')->orderBy('created_at', 'desc');

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - SOTK
    public function dataSotk()
    {
        $data = DocumentSotkUiiDalwa::orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Kurikulum Prodi
    public function dataKurikulumProdi(Request $request)
    {
        $query = DocumentKurikulumProdi::query()->orderBy('created_at', 'desc');

        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $query->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Laporan Benchmarking
    public function dataLaporanBenchmarking(Request $request)
    {
        $query = DocumentLaporanBanchmarking::with('unit')->orderBy('created_at', 'desc');

        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Laporan Evaluasi PPEPP
    public function dataLaporanEvaluasiPpepp(Request $request)
    {
        $query = DocumentLaporanEvaluasiPpepp::with('unit')->orderBy('created_at', 'desc');

        // Filter by unit_dokument_id if provided
        if ($request->has('unit_dokument_id') && $request->unit_dokument_id != '') {
            $query->where('unit_dokument_id', $request->unit_dokument_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Pedoman
    public function dataPedoman(Request $request)
    {
        $query = DocumentPedoman::with('unit')->orderBy('created_at', 'desc');

        // Filter by unit_dokument_id if provided
        if ($request->has('unit_dokument_id') && $request->unit_dokument_id != '') {
            $query->where('unit_dokument_id', $request->unit_dokument_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->nama : '-';
            })
            ->addColumn('file', function ($row) {
                return $this->generateFileButton($row->path);
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    // API untuk DataTables - Buku (External API)
    public function dataBuku(Request $request)
    {
        $params = $request->all();
        $response = Buku::getData($params);

        if ($response) {
            return response()->json($response);
        }

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    // API untuk DataTables - Jurnal (External API)
    public function dataJurnal(Request $request)
    {
        $params = $request->all();
        $response = Jurnal::getData($params);

        if ($response) {
            return response()->json($response);
        }

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    // API untuk DataTables - Prosiding (External API)
    public function dataProsiding(Request $request)
    {
        $params = $request->all();
        $response = Prosiding::getData($params);

        if ($response) {
            return response()->json($response);
        }

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    // API untuk DataTables - Artikel (External API)
    public function dataArtikel(Request $request)
    {
        $params = $request->all();
        $response = \App\Http\Services\Artikel::getData($params);

        if ($response) {
            return response()->json($response);
        }

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    // Method untuk download file yang support folder public terpisah
    public function download($path)
    {
        // Decode URL-encoded path segments
        $decodedPath = rawurldecode($path);

        $fullPath = $this->getRealFilePath($decodedPath);

        if (!$fullPath) {
            $normalizedPath = preg_replace('#/+#', '/', str_replace('\\', '/', $decodedPath));
            return response()->json([
                'error' => 'File tidak ditemukan (Download)',
                'path_requested' => $decodedPath,
                'paths_checked' => [
                    storage_path('app/public/' . $normalizedPath),
                    public_path('storage/' . $normalizedPath),
                    base_path('../public_html/storage/' . $normalizedPath),
                    base_path('../public/storage/' . $normalizedPath),
                ]
            ], 404);
        }

        return response()->download($fullPath);
    }

    // Method untuk preview file inline (tanpa download)
    public function preview($path)
    {
        $decodedPath = rawurldecode($path);
        $fullPath = $this->getRealFilePath($decodedPath);

        if (!$fullPath) {
            $normalizedPath = preg_replace('#/+#', '/', str_replace('\\', '/', $decodedPath));
            return response()->json([
                'error' => 'File tidak ditemukan',
                'path_requested' => $decodedPath,
                'paths_checked' => [
                    storage_path('app/public/' . $normalizedPath),
                    public_path('storage/' . $normalizedPath),
                    base_path('../public_html/storage/' . $normalizedPath),
                    base_path('../public/storage/' . $normalizedPath),
                ]
            ], 404);
        }

        $mimeType = mime_content_type($fullPath);
        $fileContent = file_get_contents($fullPath);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"');
    }

    public function dataRps(Request $request)
    {
        $response = Rps::table($request);

        if ($response) {
            return response()->json($response);
        }

        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    public function getRealFilePath($path)
    {
        $normalizedPath = preg_replace('#/+#', '/', str_replace('\\', '/', $path));

        $possiblePaths = [
            storage_path('app/public/' . $normalizedPath),
            public_path('storage/' . $normalizedPath),
            base_path('../public_html/storage/' . $normalizedPath),
            base_path('../public/storage/' . $normalizedPath),
        ];

        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }
}
