<?php

namespace App\Jobs;

use App\Models\DakungProdiFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;
use Exception;

class UploadDakungToGDriveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dakungFile = DakungProdiFile::with('category.accreditation.prodi')->find($this->fileId);
        
        if (!$dakungFile) {
            return;
        }

        try {
            // Path lokal sementara
            $localPath = $dakungFile->path;
            
            // Format: DAKUNG WEBSITE LPM / Nama Akreditasi / Instrumen / Nama File
            // Note: Since we set folderId in filesystems.php to point to DAKUNG WEBSITE LPM,
            // the root of 'google' disk is already inside DAKUNG WEBSITE LPM.
            $accreditationName = $dakungFile->category->accreditation->name . ' - ' . $dakungFile->category->accreditation->prodi->nama . ' (' . $dakungFile->category->accreditation->year . ')';
            $instrumentName = $dakungFile->category->name;
            $fileName = $dakungFile->original_name;

            // Remove invalid characters from names to avoid errors
            $accreditationName = preg_replace('/[<>:"\/\\\\|?*]/', '_', $accreditationName);
            $instrumentName = preg_replace('/[<>:"\/\\\\|?*]/', '_', $instrumentName);

            $possiblePaths = [
                base_path('../public_html/' . $localPath),
                public_path($localPath),
            ];
            $fullPath = null;
            foreach ($possiblePaths as $p) {
                if (file_exists($p)) {
                    $fullPath = $p;
                    break;
                }
            }

            if ($fullPath && file_exists($fullPath)) {
                $mimeType = mime_content_type($fullPath);
                
                // Gunakan Service GoogleDriveService
                $gdriveService = new GoogleDriveService();
                $gdriveFileId = $gdriveService->uploadFileNestedFromPath(
                    $fullPath,
                    $fileName,
                    $mimeType,
                    [$accreditationName, $instrumentName] // Folders
                );

                // Update database
                if ($gdriveFileId) {
                    $dakungFile->gdrive_file_id = $gdriveFileId;
                    $dakungFile->upload_status = 'uploaded';
                    $dakungFile->save();
                    
                    // NOTE: User requested NOT to delete the local file so they have it in both places.
                    // Storage::disk('public')->delete($localPath);
                } else {
                    throw new Exception("File gagal diupload atau ID tidak ditemukan dari GoogleDriveService.");
                }
            } else {
                $dakungFile->upload_status = 'failed';
                $dakungFile->save();
            }

        } catch (Exception $e) {
            \Log::error("Failed to upload Dakung file to GDrive: " . $e->getMessage());
            $dakungFile->upload_status = 'failed';
            $dakungFile->save();
        }
    }
}
