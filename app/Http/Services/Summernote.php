<?php

namespace App\Http\Services;

use Illuminate\Support\Str;
use App\Http\Services\ImageHelper;
use Illuminate\Support\Facades\File;

class Summernote
{
    /**
     * @param $isi : $request->isi
     * @param $lokasi : "upload/informasi/"
     * @return mixed dom->saveHTML();
     */
    public static function generate($isi, $lokasi)
    {
        // Hindari warning DOM
        libxml_use_internal_errors(true);

        // Pastikan string UTF-8
        if (!mb_detect_encoding($isi, 'UTF-8', true)) {
            $isi = mb_convert_encoding($isi, 'UTF-8');
        }

        // Paksa DOM pakai UTF-8
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8"?>' . $isi, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        $paths  = [];

        // Normalisasi path lokasi (tanpa awalan public_path, akhiri dengan /)
        $lokasi  = substr($lokasi, 0, -1);

        $baseDir = public_path($lokasi);
        if (!is_dir($baseDir)) {
            @mkdir($baseDir, 0775, true);
        }

        foreach ($images as $img) {
            $dataImage = $img->getAttribute('src');

            // Deteksi data URI base64 (lebih aman daripada explode(':'))
            if (preg_match('#^data:image/([a-zA-Z0-9.+-]+);base64,(.+)$#', $dataImage, $m)) {

                $compressedUrl = ImageHelper::saveBase64Image($dataImage, $img->getAttribute('data-filename'), $lokasi);

                $publicUrl = $compressedUrl;
                $imageName = basename(parse_url($compressedUrl, PHP_URL_PATH));
                $img->setAttribute('src', $publicUrl);
                // Simpan kembali data-filename agar rapi bila nanti dibersihkan
                $img->setAttribute('data-filename', $imageName);

                $fullPath = $baseDir . $imageName;
                $paths[] = $fullPath;
            }
        }

        // ambil hasil dan hapus baris XML
        $html = $dom->saveHTML();
        $html = preg_replace('/^<\?xml.*?\?>/i', '', $html); // ← FIX utama

        libxml_clear_errors();

        return [
            'data'  => $html,
            'paths' => $paths,
        ];
    }

    /**
     * @param $edit : data edit from eluquent, MUST HAVE column isi
     * @param $isi : $request->isi
     * @param $lokasi : "upload/informasi/"
     * @return mixed dom->saveHTML();
     */
    public static function generateForEdit($editIsi, $isiRequest, $lokasi)
    {
        libxml_use_internal_errors(true);

        // Helper kecil: buat DOMDocument UTF-8 & load HTML dengan deklarasi UTF-8
        $makeDom = function (string $html): \DOMDocument {
            // Pastikan string UTF-8 valid
            if (!mb_detect_encoding($html, 'UTF-8', true)) {
                $html = mb_convert_encoding($html, 'UTF-8');
            }
            $dom = new \DOMDocument('1.0', 'UTF-8');
            // Trik xml prolog utk paksa parser DOM pakai UTF-8
            $dom->loadHTML('<?xml encoding="UTF-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            return $dom;
        };

        $isiLama   = [];
        $isiBaru   = [];
        $isiDelete = [];

        // --- Parse isi lama (jika ada) ---
        if (!empty($editIsi)) {
            $domOld  = $makeDom($editIsi);
            $images  = $domOld->getElementsByTagName('img');
            foreach ($images as $img) {
                $isiLama[] = [
                    'src'      => $img->getAttribute('src'),
                    'filename' => $img->getAttribute('data-filename'),
                ];
            }
        }

        // --- Parse isi baru ---
        $dom    = $makeDom($isiRequest);
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $dataImage = $img->getAttribute('src');

            // Deteksi data URI base64
            if (preg_match('#^data:image/([a-zA-Z0-9.+-]+);base64,#', $dataImage)) {
                $fileName = $img->getAttribute('data-filename') ?: 'image.png';

                // Ekstrak & decode base64
                [$meta, $payload] = explode(',', $dataImage, 2);
                $binary = base64_decode($payload, true);
                if ($binary === false) {
                    // Jika gagal decode, lewati tanpa mengubah img
                    continue;
                }

                // // Buat nama file unik yang aman
                // $cleanBase = preg_replace('/[^\w\-.]+/u', '_', pathinfo($fileName, PATHINFO_FILENAME));
                // $ext       = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) ?: 'png';
                // $imageName = 'Foto_' . date('Ymd_His') . '_' . Str::uuid() . '_' . $cleanBase . '.' . $ext;

                // // Simpan ke public
                // $fullPath = public_path($lokasi . $imageName);
                // if (!is_dir(dirname($fullPath))) {
                //     @mkdir(dirname($fullPath), 0775, true);
                // }
                // file_put_contents($fullPath, $binary);

                // // Update src ke URL publik
                // $img->setAttribute('src', asset($lokasi . $imageName));
                // // (opsional) simpan kembali data-filename agar tracking penghapusan rapi
                // $img->setAttribute('data-filename', $imageName);

                $lokasi = substr($lokasi, 0, -1);
                $compressedUrl = ImageHelper::saveBase64Image($dataImage, $img->getAttribute('data-filename'), $lokasi);

                $publicUrl = $compressedUrl;
                $imageName = basename(parse_url($compressedUrl, PHP_URL_PATH));
                $img->setAttribute('src', $publicUrl);
                // Simpan kembali data-filename agar rapi bila nanti dibersihkan
                $img->setAttribute('data-filename', $imageName);
            } else {
                // Bukan data URI → masukkan ke daftar baru
                $isiBaru[] = [
                    'src'      => $img->getAttribute('src'),
                    'filename' => $img->getAttribute('data-filename'),
                ];
            }
        }

        // --- Tentukan gambar yang dihapus (ada di lama, tidak ada di baru) ---
        foreach ($isiLama as $il) {
            $stillExists = false;
            foreach ($isiBaru as $ib) {
                if ($il['src'] === $ib['src']) {
                    $stillExists = true;
                    break;
                }
            }
            if (!$stillExists) {
                $isiDelete[] = $il;
            }
        }

        // --- Hapus file yang tidak dipakai ---
        foreach ($isiDelete as $img) {
            $src      = rawurldecode($img['src']);
            $filename = $img['filename'];

            // Jika filename valid, hapus berdasarkan nama file
            if (!empty($filename)) {
                @File::delete(public_path($lokasi . $filename));
                continue;
            }

            // Fallback: coba ambil basename dari src
            $base = basename(parse_url($src, PHP_URL_PATH) ?? '');
            if ($base) {
                @File::delete(public_path($lokasi . $base));
            }
        }

        // Simpan HTML; DOM sudah UTF-8, jadi aman utk teks Arab/emoji
        // ambil hasil dan hapus baris XML
        $isi = $dom->saveHTML();
        $isi = preg_replace('/^<\?xml.*?\?>/i', '', $isi); // ← FIX utama

        libxml_clear_errors();

        return [
            'data'  => $isi,
            'paths' => [],
        ];
    }

    /**
     * @param $edit : data edit from eluquent, MUST HAVE column isi
     * @param $isi : $request->isi
     * @param $lokasi : "upload/informasi/"
     */
    public static function deleteImage($isi, $lokasi)
    {
        // delete image summernote
        $dom = new \DomDocument();
        @$dom->loadHTML($isi, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');

        $paths = [];
        foreach ($images as $k => $img) {
            $dataImage = rawurldecode($img->getAttribute('src'));
            $extensi = explode('.', $img->getAttribute('data-filename'));
            $extensi = end($extensi);
            $filename = pathinfo($dataImage, PATHINFO_FILENAME);
            $path = $lokasi . $filename . '.' . $extensi;
            if ($filename != null) {
                if (File::delete(public_path($path))) {
                    $paths[] = $path;
                };
            }
            // $dataImage = $img->getAttribute('src');
            // $dataImage = str_replace(config('app.url'), '', $dataImage);
            // $dataImage = public_path($dataImage);
            // $dataImage = rawurldecode($dataImage);
            // if (File::exists($dataImage)) {
            //     File::delete($dataImage);
            // }
        }

        return [
            'status' => true,
            'paths' => $paths,
        ];
    }

    /**
     * @param $paths : data paths
     */
    public static function deleteImageFromPaths($paths)
    {
        if (!$paths) {
            return false;
        }

        foreach ($paths as $key => $value) {
            File::delete($value);
        }

        return true;
    }

    /**
     * clean formated from summernote
     * @param mixed $text
     * @return string
     */
    public static function clean($text)
    {
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true); // Suppress errors due to malformed HTML
        $dom->loadHTML($text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Find all <img> tags and remove them
        $images = $dom->getElementsByTagName('img');
        while ($images->length > 0) {
            $images->item(0)->parentNode->removeChild($images->item(0));
        }

        // Output cleaned HTML
        $cleanedHtml = $dom->saveHTML();
        $cleanedHtml = trim(strip_tags($cleanedHtml));

        return $cleanedHtml;
    }
}
