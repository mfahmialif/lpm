<?php

namespace App\Jobs;

use App\Models\DocumentDiferensiasiMisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\GoogleDriveService;
use Exception;
use Illuminate\Support\Facades\Log;

class UploadDiferensiasiMisiToGDriveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $documentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = DocumentDiferensiasiMisi::find($this->documentId);

        if (!$document || !$document->path) {
            return;
        }

        try {
            $localPath = $document->path;
            $extension = pathinfo($localPath, PATHINFO_EXTENSION);
            
            // Format nama file di Google Drive: Nama Dokumen + ekstensi
            if (!empty($document->nama)) {
                $cleanDocName = preg_replace('/[<>:"\/\\\\|?*]/', '_', $document->nama);
                $fileName = $cleanDocName . ($extension ? '.' . $extension : '');
            } else {
                $fileName = basename($localPath);
            }

            $possiblePaths = [
                base_path('../public_html/storage/' . $localPath),
                base_path('../public_html/' . $localPath),
                public_path('storage/' . $localPath),
                public_path($localPath),
                storage_path('app/public/' . $localPath),
            ];

            $fullPath = null;
            foreach ($possiblePaths as $p) {
                if (file_exists($p) && is_file($p)) {
                    $fullPath = $p;
                    break;
                }
            }

            if ($fullPath && file_exists($fullPath)) {
                $mimeType = @mime_content_type($fullPath) ?: 'application/octet-stream';

                // Gunakan GoogleDriveService untuk upload ke folder Dokumen Diferensiasi Misi
                $gdriveService = new GoogleDriveService();
                $gdriveFileId = $gdriveService->uploadFileNestedFromPath(
                    $fullPath,
                    $fileName,
                    $mimeType,
                    ['Dokumen Diferensiasi Misi']
                );

                if ($gdriveFileId) {
                    $document->gdrive_file_id = $gdriveFileId;
                    $document->upload_status = 'uploaded';
                    $document->save();
                } else {
                    $document->upload_status = 'failed';
                    $document->save();
                    Log::warning("Upload Diferensiasi Misi ID {$document->id} ke GDrive gagal (tidak mendapatkan file ID).");
                }
            } else {
                $document->upload_status = 'failed';
                $document->save();
                Log::warning("File lokal Diferensiasi Misi ID {$document->id} tidak ditemukan di storage.");
            }
        } catch (Exception $e) {
            Log::error("Failed to upload Diferensiasi Misi file to GDrive: " . $e->getMessage());
            if ($document) {
                $document->upload_status = 'failed';
                $document->save();
            }
        }
    }
}
