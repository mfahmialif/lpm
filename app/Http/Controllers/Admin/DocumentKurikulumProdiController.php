<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentKurikulumProdi;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DocumentKurikulumProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prodis = Prodi::orderBy('nama')->get();
        return view('admin.document-kurikulum-prodi.index', compact('prodis'));
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request)
    {
        $query = DocumentKurikulumProdi::with('prodi')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('prodi_nama', function ($row) {
                return $row->prodi ? $row->prodi->nama : '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('file', function ($row) {
                if ($row->path) {
                    return '<a href="' . asset('storage/' . $row->path) . '" target="_blank" class="btn btn-sm btn-primary"><i class="ti ti-download"></i> Download</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->rawColumns(['file', 'prodi_nama'])
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
            'fakultas' => 'nullable|string|max:255',
            'prodi_id' => 'nullable|exists:prodis,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'fakultas' => $request->fakultas,
                'prodi_id' => $request->prodi_id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/kurikulum-prodi', $filename, 'public');
                $data['path'] = $path;
            }

            DocumentKurikulumProdi::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Kurikulum Prodi berhasil ditambahkan.'
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
            'perihal' => 'nullable|string|max:255',
            'yang_mengeluarkan' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'prodi_id' => 'nullable|exists:prodis,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
        ]);

        try {
            $document = DocumentKurikulumProdi::findOrFail($id);

            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'fakultas' => $request->fakultas,
                'prodi_id' => $request->prodi_id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/kurikulum-prodi', $filename, 'public');
                $data['path'] = $path;
            }

            $document->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Kurikulum Prodi berhasil diperbarui.'
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
            $document = DocumentKurikulumProdi::findOrFail($id);

            // Delete file if exists
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            $document->delete();

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Kurikulum Prodi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
