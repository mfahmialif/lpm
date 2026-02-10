<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentLaporanEvaluasiPpepp;
use App\Models\UnitDokument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DocumentLaporanEvaluasiPpeppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = UnitDokument::where('jenis', 'prodi')->get();
        return view('admin.document-laporan-evaluasi-ppepp.index', compact('units'));
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request)
    {
        $query = DocumentLaporanEvaluasiPpepp::with('unit')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                if ($row->path) {
                    return '<a href="' . asset('storage/' . $row->path) . '" target="_blank" class="btn btn-sm btn-primary"><i class="ti ti-download"></i> Download</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->addColumn('unit_nama', function ($row) {
                return $row->unit ? $row->unit->nama : '<span class="badge bg-secondary">-</span>';
            })
            ->rawColumns(['file', 'unit_nama'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_surat' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'yang_mengeluarkan' => 'nullable|string|max:255',
            'unit_dokument_id' => 'required|exists:units_dokument,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'unit_dokument_id' => $request->unit_dokument_id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/laporan-evaluasi-ppepp', $filename, 'public');
                $data['path'] = $path;
            }

            DocumentLaporanEvaluasiPpepp::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Laporan Evaluasi PPEPP berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_surat' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
            'unit_dokument_id' => 'required|exists:units_dokument,id',
        ]);

        try {
            $document = DocumentLaporanEvaluasiPpepp::findOrFail($id);

            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'unit_dokument_id' => $request->unit_dokument_id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/laporan-evaluasi-ppepp', $filename, 'public');
                $data['path'] = $path;
            }

            $document->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Laporan Evaluasi PPEPP berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $document = DocumentLaporanEvaluasiPpepp::findOrFail($id);

            // Delete file if exists
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            $document->delete();

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Laporan Evaluasi PPEPP berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
