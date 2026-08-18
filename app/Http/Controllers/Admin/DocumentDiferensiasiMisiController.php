<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentDiferensiasiMisi;
use App\Jobs\UploadDiferensiasiMisiToGDriveJob;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DocumentDiferensiasiMisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.document-diferensiasi-misi.index');
    }

    /**
     * Helper to get public directory path supporting separated public_html structure on server.
     */
    private function getPublicDirectory($subpath = '')
    {
        if (is_dir(base_path('../public_html'))) {
            $base = base_path('../public_html');
        } else {
            $base = public_path();
        }
        return $subpath ? $base . '/' . ltrim($subpath, '/\\') : $base;
    }

    /**
     * Helper to delete physical file across possible locations.
     */
    private function deletePhysicalFile($path)
    {
        if (!$path) return;
        $cleanPath = ltrim(str_replace('\\', '/', $path), '/');
        $possiblePaths = [
            base_path('../public_html/' . $cleanPath),
            base_path('../public_html/storage/' . $cleanPath),
            public_path($cleanPath),
            public_path('storage/' . $cleanPath),
            storage_path('app/public/' . $cleanPath),
        ];
        foreach ($possiblePaths as $filePath) {
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request)
    {
        $query = DocumentDiferensiasiMisi::orderBy('created_at', 'desc');

        return DataTables::of($query)
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
                if ($row->path) {
                    $cleanPath = ltrim(str_replace('\\', '/', $row->path), '/');
                    $previewUrl = route('institution-document.preview', ['path' => $cleanPath]);
                    $html = '<a href="' . $previewUrl . '" target="_blank" class="btn btn-sm btn-primary"><i class="ti ti-download"></i> Download</a>';
                    if ($row->upload_status === 'uploaded' && $row->gdrive_file_id) {
                        $html .= ' <a href="https://drive.google.com/file/d/' . $row->gdrive_file_id . '/view" target="_blank" class="btn btn-sm btn-outline-success" title="Buka di Google Drive"><i class="ti ti-brand-google-drive"></i> Drive</a>';
                    } elseif ($row->upload_status === 'pending') {
                        $html .= ' <span class="badge bg-warning" title="Sedang sinkronisasi ke Google Drive"><i class="ti ti-clock"></i> Sync Drive</span>';
                    } elseif ($row->upload_status === 'failed') {
                        $html .= ' <span class="badge bg-danger" title="Gagal upload ke Google Drive"><i class="ti ti-alert-circle"></i> Gagal Drive</span>';
                    }
                    return $html;
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->rawColumns(['status_badge', 'file'])
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
            'file' => [
                'nullable',
                'file',
                'max:10240', // Max 10MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, $allowedExtensions)) {
                            $fail('The file must be a file of type: ' . implode(', ', $allowedExtensions) . '.');
                        }
                    }
                }
            ],
            'status' => 'nullable|in:acc,tolak',
        ]);

        try {
            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'status' => $request->status,
            ];

            // Handle file upload directly to public_html/documents or public/documents
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $uploadDir = $this->getPublicDirectory('documents/diferensiasi-misi');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $file->move($uploadDir, $filename);

                $data['path'] = 'documents/diferensiasi-misi/' . $filename;
                $data['upload_status'] = 'pending';
            }

            $document = DocumentDiferensiasiMisi::create($data);

            // Dispatch job to upload to Google Drive
            if ($request->hasFile('file')) {
                dispatch(new UploadDiferensiasiMisiToGDriveJob($document->id));
            }

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Diferensiasi Misi berhasil ditambahkan.'
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
            'file' => [
                'nullable',
                'file',
                'max:10240', // Max 10MB
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, $allowedExtensions)) {
                            $fail('The file must be a file of type: ' . implode(', ', $allowedExtensions) . '.');
                        }
                    }
                }
            ],
            'status' => 'nullable|in:acc,tolak',
        ]);

        try {
            $document = DocumentDiferensiasiMisi::findOrFail($id);

            $data = [
                'nama' => $request->nama,
                'no_surat' => $request->no_surat,
                'perihal' => $request->perihal,
                'yang_mengeluarkan' => $request->yang_mengeluarkan,
                'status' => $request->status,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file physically
                $this->deletePhysicalFile($document->path);

                // Delete old file from Google Drive if exists
                if ($document->gdrive_file_id) {
                    try {
                        $gdriveService = new GoogleDriveService();
                        $gdriveService->deleteFile($document->gdrive_file_id);
                    } catch (\Exception $e) {
                        Log::warning("Gagal hapus file lama dari GDrive: " . $e->getMessage());
                    }
                }

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $uploadDir = $this->getPublicDirectory('documents/diferensiasi-misi');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $file->move($uploadDir, $filename);

                $data['path'] = 'documents/diferensiasi-misi/' . $filename;
                $data['gdrive_file_id'] = null;
                $data['upload_status'] = 'pending';
            }

            $document->update($data);

            // Dispatch job to upload new file to Google Drive
            if ($request->hasFile('file')) {
                dispatch(new UploadDiferensiasiMisiToGDriveJob($document->id));
            }

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Diferensiasi Misi berhasil diperbarui.'
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
            $document = DocumentDiferensiasiMisi::findOrFail($id);

            // Delete file from physical storage
            $this->deletePhysicalFile($document->path);

            // Delete file from Google Drive if exists
            if ($document->gdrive_file_id) {
                try {
                    $gdriveService = new GoogleDriveService();
                    $gdriveService->deleteFile($document->gdrive_file_id);
                } catch (\Exception $e) {
                    Log::warning("Gagal hapus file dari GDrive: " . $e->getMessage());
                }
            }

            $document->delete();

            return response()->json([
                'status' => true,
                'message' => 'Dokumen Diferensiasi Misi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}


