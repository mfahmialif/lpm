<?php

namespace App\Http\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Kompres dan upload semua gambar base64 di dalam HTML.
     *
     * @param  string  $html
     * @param  string  $folder  Folder penyimpanan di public/storage/
     * @return string  HTML yang sudah diperbarui dengan URL gambar
     */
    public static function compressHtmlImages(string $html, string $folder = 'storage/image-compressed'): string
    {
        if (trim($html) === '') return $html;

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $imgs = $dom->getElementsByTagName('img');
        $targets = [];

        foreach ($imgs as $img) {
            $src = $img->getAttribute('src');
            if (strpos($src, 'data:') === 0) {
                $targets[] = $img;
            }
        }

        foreach ($targets as $img) {
            $src = $img->getAttribute('src');
            $filename = $img->getAttribute('data-filename') ?: 'image';
            $newUrl = self::saveBase64Image($src, $filename, $folder);

            if ($newUrl) {
                $img->setAttribute('src', $newUrl);
                $img->removeAttribute('data-filename');
            }
        }

        return $dom->saveHTML();
    }

    /**
     * Decode, kompres, dan simpan gambar base64 ke public/storage/{folder}
     *
     * @param  string  $dataUri
     * @param  string  $filename
     * @param  string  $folder
     * @return string|null  URL publik gambar yang disimpan
     */
    public static function saveBase64Image(string $dataUri, string $filename = 'image', string $folder = 'storage/image-compressed'): ?string
    {
        if (!preg_match('#^data:(image/[^;]+);base64,(.+)$#', $dataUri, $m)) return null;

        $binary = base64_decode($m[2]);
        if ($binary === false) return null;

        $img = Image::make($binary)->orientate();

        // Resize maksimal 1280x1280
        $img->resize(1280, 1280, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });

        $supportsWebp = self::gdSupports('WebP');

        $base = \Str::slug(pathinfo($filename, PATHINFO_FILENAME), '_');
        $stamp = now()->format('YmdHis') . '_' . uniqid();

        if ($supportsWebp) {
            $encoded = (string) $img->encode('webp', 70);
            $filename = "{$base}_{$stamp}.webp";
        } else {
            $encoded = (string) $img->encode('jpg', 75);
            $filename = "{$base}_{$stamp}.jpg";
        }

        // Simpan langsung ke public/storage/{folder}
        $savePath = public_path("{$folder}");
        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
        }

        file_put_contents($savePath . '/' . $filename, $encoded);

        // URL publik (bisa langsung diakses via domain)
        return asset("{$folder}/{$filename}");
    }

    /**
     * Cek dukungan GD untuk format tertentu (misal WebP)
     */
    protected static function gdSupports(string $feature): bool
    {
        return false;
        // if (!function_exists('gd_info')) return false;
        // $info = gd_info();
        // return !empty($info["{$feature} Support"]);
    }

    public static function compressUploadedImage(UploadedFile $file, string $folder = 'storage/image-compressed', int $maxSize = 1280, int $qualityWebp = 70, int $qualityJpg = 75): ?string
    {
        // Baca file ke Intervention Image + perbaiki orientasi EXIF
        $img = Image::make($file->getRealPath())->orientate();

        // Resize dengan sisi terpanjang = $maxSize (tanpa upscale)
        $img->resize($maxSize, $maxSize, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });

        $supportsWebp = self::gdSupports('WebP');

        // Buat nama file
        $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base     = Str::slug($origName ?: 'image', '_');
        $stamp    = date('YmdHis') . '_' . uniqid();

        if ($supportsWebp) {
            // Simpan sebagai WebP
            $encoded  = (string) $img->encode('webp', $qualityWebp);
            $filename = "{$base}_{$stamp}.webp";
        } else {
            // Fallback JPG: jika sumber punya alpha (png/webp), flatten ke putih dulu agar tidak jadi hitam
            if (in_array(strtolower($file->getClientOriginalExtension()), ['png', 'webp'])) {
                $canvas = Image::canvas($img->width(), $img->height(), '#ffffff');
                $canvas->insert($img);
                $img = $canvas;
            }
            $encoded  = (string) $img->encode('jpg', $qualityJpg);
            $filename = "{$base}_{$stamp}.jpg";
        }

        // Pastikan folder ada -> public/storage/{folder}
        $savePath = public_path("{$folder}");
        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
        }

        // Tulis file
        file_put_contents($savePath . '/' . $filename, $encoded);

        // Kembalikan URL publik
        return asset("{$folder}/{$filename}");
    }
}
