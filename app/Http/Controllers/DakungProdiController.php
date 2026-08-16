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

        $possiblePaths = [
            base_path('../public_html/' . $relativePath),
            public_path($relativePath),
        ];

        foreach ($possiblePaths as $p) {
            if (file_exists($p)) {
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
        if ($file->upload_status === 'uploaded' && $file->gdrive_file_id) {
            return redirect("https://drive.google.com/uc?id={$file->gdrive_file_id}&export=download");
        }

        $filePath = $this->resolveFilePath($file->path);

        if (!$filePath) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $file->original_name);
    }

    /**
     * Preview the specified file inline.
     */
    public function preview(DakungProdiFile $file)
    {
        if ($file->upload_status === 'uploaded' && $file->gdrive_file_id) {
            return redirect("https://drive.google.com/file/d/{$file->gdrive_file_id}/preview");
        }

        $filePath = $this->resolveFilePath($file->path);

        if (!$filePath) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
        ]);
    }
}
