<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Requirement;
use Illuminate\Http\Request;
use App\Models\Accreditation;
use App\Models\DakungProdiFile;
use Illuminate\Support\Facades\Cookie;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $accreditation = Accreditation::query();

        if ($request->search) {
            $accreditation->where('name', 'like', '%' . $request->search . '%');
        }

        $accreditation = $accreditation->paginate(10); // Paginate 10 items per page
        return view('accreditation.index', compact('accreditation'));
    }

    public function detail(Request $request, Accreditation $accreditation)
    {
        $requirements = Requirement::where('accreditation_id', $accreditation->id)->whereNull('parent_id')->get();
        $dakungProdiCategories = \App\Models\DakungProdiCategory::with(['files' => function($q) {
                                        $q->orderBy('id', 'asc');
                                    }])
                                    ->where('accreditation_id', $accreditation->id)
                                    ->orderBy('order_index', 'asc')
                                    ->orderBy('id', 'asc')
                                    ->get();
        return view('accreditation.detail.index', compact('accreditation', 'requirements', 'dakungProdiCategories'));
    }

    private function resolveFilePath($relativePath)
    {
        if (!$relativePath) {
            return null;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $cleanPathWithoutStorage = preg_replace('#^storage/#i', '', $cleanPath);
        $docPath = preg_replace('#^documents/#i', '', $cleanPathWithoutStorage);

        $possiblePaths = [
            // 1. Production Server public_html/documents directory
            base_path('../public_html/documents/' . $docPath),
            base_path('../public_html/' . $cleanPathWithoutStorage),
            base_path('../public_html/storage/' . $cleanPathWithoutStorage),

            // 2. Local / Standard public directory
            public_path('documents/' . $docPath),
            public_path($cleanPathWithoutStorage),
            public_path('storage/' . $cleanPathWithoutStorage),

            // 3. Laravel storage app directory
            storage_path('app/public/' . $cleanPathWithoutStorage),
            storage_path('app/public/documents/' . $docPath),
            storage_path('app/' . $cleanPathWithoutStorage),
            base_path('../public/storage/' . $cleanPathWithoutStorage),
            base_path($cleanPathWithoutStorage),
        ];

        foreach ($possiblePaths as $p) {
            if (file_exists($p) && is_file($p)) {
                return $p;
            }
        }

        return null;
    }

    /**
     * Redirect or stream the server document file via short link.
     */
    public function shortLink($code)
    {
        $doc = DakungProdiFile::findDocumentByCode($code);

        if (!$doc) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        // 1. Serve physical file directly from server if it exists
        if (!empty($doc['path'])) {
            $filePath = $this->resolveFilePath($doc['path']);
            if ($filePath && file_exists($filePath)) {
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                // Word/Excel: preview menggunakan client-side viewer langsung di browser
                $officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'csv'];
                if (in_array($extension, $officeExtensions)) {
                    $cleanPath = ltrim(str_replace('\\', '/', $doc['path']), '/');
                    $fileUrl = asset($cleanPath);
                    $downloadUrl = route('institution-document.download', ['path' => $cleanPath]);
                    $filename = $doc['original_name'] ?? basename($filePath);

                    return view('preview.office', compact('filename', 'fileUrl', 'downloadUrl', 'extension'));
                }

                $mimeTypes = [
                    'pdf'  => 'application/pdf',
                    'png'  => 'image/png',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif'  => 'image/gif',
                    'webp' => 'image/webp',
                    'svg'  => 'image/svg+xml',
                    'txt'  => 'text/plain',
                ];
                $mimeType = $mimeTypes[$extension] ?? (@mime_content_type($filePath) ?: 'application/octet-stream');

                return response()->file($filePath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . ($doc['original_name'] ?? basename($filePath)) . '"'
                ]);
            }
        }

        // 2. Fallback to Google Drive if file is stored in drive
        if (!empty($doc['gdrive_file_id'])) {
            return redirect('https://drive.google.com/file/d/' . $doc['gdrive_file_id'] . '/view');
        }

        // 3. Fallback to preview route
        if (!empty($doc['path'])) {
            $cleanPath = ltrim(str_replace('\\', '/', $doc['path']), '/');
            return redirect(route('institution-document.preview', ['path' => $cleanPath]));
        }

        abort(404, 'Alamat dokumen tidak tersedia di server.');
    }
}
