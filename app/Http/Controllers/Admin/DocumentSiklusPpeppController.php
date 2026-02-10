<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentSiklusPpepp;
use App\Models\UnitDokument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DocumentSiklusPpeppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = UnitDokument::where('jenis', 'prodi')->get();
        return view('admin.document-siklus-ppepp.index', compact('units'));
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request)
    {
        $query = DocumentSiklusPpepp::query()->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                if ($row->path) {
                    return '<a href="' . asset('storage/' . $row->path) . '" target="_blank" class="btn btn-sm btn-primary"><i class="ti ti-download"></i> Download</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->rawColumns(['file'])
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
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'unit_dokumen_id' => 'required|exists:units_dokument,id',
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'unit_dokument_id' => $request->unit_dokumen_id,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/siklus-ppepp', $filename, 'public');
                $data['path'] = $path;
            }

            DocumentSiklusPpepp::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Siklus PPEPP berhasil ditambahkan.'
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
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'unit_dokumen_id' => 'required|exists:units_dokument,id',
        ]);

        try {
            $document = DocumentSiklusPpepp::findOrFail($id);

            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'unit_dokument_id' => $request->unit_dokumen_id,
            ];

            if ($request->hasFile('file')) {
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/siklus-ppepp', $filename, 'public');
                $data['path'] = $path;
            }

            $document->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Siklus PPEPP berhasil diperbarui.'
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
            $document = DocumentSiklusPpepp::findOrFail($id);

            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            $document->delete();

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Siklus PPEPP berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
