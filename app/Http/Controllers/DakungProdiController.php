<?php

namespace App\Http\Controllers;

use App\Models\DakungProdiFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DakungProdiController extends Controller
{
    private function resolveFilePath($relativePath)
    {
        if (!$relativePath) {
            return null;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $docPath = preg_replace('#^documents/#i', '', $cleanPath);

        $possiblePaths = [
            // 1. Production Server public_html/documents directory
            base_path('../public_html/documents/' . $docPath),
            base_path('../public_html/' . $cleanPath),
            base_path('../public_html/storage/' . $cleanPath),

            // 2. Local / Standard public directory
            public_path('documents/' . $docPath),
            public_path($cleanPath),
            public_path('storage/' . $cleanPath),

            // 3. Laravel storage app directory
            storage_path('app/public/' . $cleanPath),
            storage_path('app/' . $cleanPath),
            base_path($cleanPath),
        ];

        foreach ($possiblePaths as $p) {
            if (file_exists($p) && is_file($p)) {
                return $p;
            }
        }

        return null;
    }

    /**
     * Download the specified file.
     */
    public function download(DakungProdiFile $file)
    {
        $filePath = $this->resolveFilePath($file->path);

        if ($filePath && file_exists($filePath)) {
            return response()->download($filePath, $file->original_name);
        }

        if (!empty($file->path)) {
            return redirect(asset($file->path));
        }

        abort(404, 'File tidak ditemukan di server.');
    }

    /**
     * Preview the specified file inline.
     */
    public function preview(DakungProdiFile $file)
    {
        $filePath = $this->resolveFilePath($file->path);

        if ($filePath && file_exists($filePath)) {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Word/Excel: preview menggunakan client-side viewer langsung di browser
            $officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'csv'];
            if (in_array($extension, $officeExtensions)) {
                $cleanPath = ltrim(str_replace('\\', '/', $file->path), '/');
                $fileUrl = asset($cleanPath);
                $downloadUrl = route('dakung-prodi.download', $file->id);
                $filename = $file->original_name ?? basename($filePath);

                return view('preview.office', compact('filename', 'fileUrl', 'downloadUrl', 'extension'));
            }

            $mimeType = @mime_content_type($filePath) ?: 'application/octet-stream';

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . ($file->original_name ?? basename($filePath)) . '"'
            ]);
        }

        if (!empty($file->path)) {
            return redirect(asset($file->path));
        }

        abort(404, 'File tidak ditemukan di server.');
    }
}
