<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected $service;
    protected $mainFolderId;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');

        // Set refresh token to get new access token
        $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        $this->service = new Google_Service_Drive($client);
        $this->mainFolderId = env('GOOGLE_DRIVE_FOLDER');
    }

    /**
     * Upload file ke Google Drive dengan struktur folder bertingkat (nested).
     *
     * @param string $filePath Path file lokal
     * @param string $fileName Nama file
     * @param string $mimeType MIME type file
     * @param array $folders Array nama folder bertingkat, misal: ['Akreditasi A', 'Instrumen 1']
     * @return string|null ID File Google Drive
     */
    public function uploadFileNestedFromPath(string $filePath, string $fileName, string $mimeType, array $folders): ?string
    {
        try {
            $parentId = $this->mainFolderId;
            
            // Loop untuk membuat folder bertingkat
            foreach ($folders as $folderName) {
                $parentId = $this->findOrCreateFolder($folderName, $parentId);
            }

            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$parentId],
            ]);

            $content = file_get_contents($filePath);
            $uploadedFile = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink',
            ]);

            $permission = new Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->service->permissions->create($uploadedFile->id, $permission);

            // Mengembalikan file ID (karena database kita butuh file ID)
            return $uploadedFile->id;
        } catch (\Exception $e) {
            Log::error('Google Drive upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload file ke Google Drive di dalam subfolder tertentu.
     *
     * @param UploadedFile $file File yang akan diupload
     * @param string $folderName Nama subfolder di dalam folder utama
     * @return string|null Link Google Drive file yang diupload
     */
    public function uploadFile(UploadedFile $file, string $folderName): ?string
    {
        try {
            // Cari atau buat subfolder
            $subFolderId = $this->findOrCreateFolder($folderName, $this->mainFolderId);

            // Buat metadata file
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => time() . '_' . $file->getClientOriginalName(),
                'parents' => [$subFolderId],
            ]);

            // Upload file
            $content = file_get_contents($file->getRealPath());
            $uploadedFile = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink',
            ]);

            // Set permission agar bisa diakses oleh siapa saja yang memiliki link
            $permission = new Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->service->permissions->create($uploadedFile->id, $permission);

            return 'https://drive.google.com/file/d/' . $uploadedFile->id . '/view';
        } catch (\Exception $e) {
            Log::error('Google Drive upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload file ke Google Drive dari path lokal.
     *
     * @param string $filePath Path file lokal
     * @param string $fileName Nama file
     * @param string $mimeType MIME type file
     * @param string $folderName Nama subfolder
     * @return string|null Link Google Drive
     */
    public function uploadFileFromPath(string $filePath, string $fileName, string $mimeType, string $folderName): ?string
    {
        try {
            $subFolderId = $this->findOrCreateFolder($folderName, $this->mainFolderId);

            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$subFolderId],
            ]);

            $content = file_get_contents($filePath);
            $uploadedFile = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink',
            ]);

            $permission = new Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $this->service->permissions->create($uploadedFile->id, $permission);

            return 'https://drive.google.com/file/d/' . $uploadedFile->id . '/view';
        } catch (\Exception $e) {
            Log::error('Google Drive upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hapus file dari Google Drive berdasarkan link.
     *
     * @param string|null $driveLink Link Google Drive
     * @return bool
     */
    public function deleteFile(?string $driveLink): bool
    {
        if (!$driveLink) {
            return false;
        }

        try {
            $fileId = $this->extractFileId($driveLink);
            if ($fileId) {
                $this->service->files->delete($fileId);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Google Drive delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cari folder berdasarkan nama di parent tertentu, buat jika belum ada.
     *
     * @param string $folderName Nama folder
     * @param string $parentId ID parent folder
     * @return string Folder ID
     */
    protected function findOrCreateFolder(string $folderName, string $parentId): string
    {
        // Cari folder yang sudah ada
        $query = sprintf(
            "name='%s' and '%s' in parents and mimeType='application/vnd.google-apps.folder' and trashed=false",
            addslashes($folderName),
            $parentId
        );

        $results = $this->service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)',
            'spaces' => 'drive',
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        // Buat folder baru jika belum ada
        $folderMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId],
        ]);

        $folder = $this->service->files->create($folderMetadata, [
            'fields' => 'id',
        ]);

        return $folder->id;
    }

    /**
     * Ekstrak file ID dari link Google Drive.
     *
     * @param string $link Link Google Drive
     * @return string|null File ID
     */
    protected function extractFileId(string $link): ?string
    {
        // Pattern: https://drive.google.com/file/d/{fileId}/view
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $link, $matches)) {
            return $matches[1];
        }

        // Pattern: https://drive.google.com/open?id={fileId}
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $link, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
