<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DakungProdiFile extends Model
{
    protected $table = 'dakung_prodi_files';
    protected $guarded = [];

    private const MASK = 0x5B3C8; // 373704
    private const OFFSET = 100000;
    private const CHARS = '7wKyN2xVbT4mQpRsZ9fGcLjKhFdCe1u3t5i6o8gAaBDEHIJLMOPRUWXY'; // 57 chars

    public function category()
    {
        return $this->belongsTo(DakungProdiCategory::class, 'dakung_prodi_category_id');
    }

    /**
     * Generate an obfuscated unique short code from ID.
     */
    public static function encodeCode(int $id): string
    {
        $num = ($id ^ self::MASK) + self::OFFSET;
        $base = strlen(self::CHARS);
        $code = '';
        while ($num > 0) {
            $code = self::CHARS[$num % $base] . $code;
            $num = intdiv($num, $base);
        }
        return $code ?: self::CHARS[0];
    }

    /**
     * Decode the short code back to file ID.
     */
    public static function decodeCode(string $code): ?int
    {
        $base = strlen(self::CHARS);
        $num = 0;
        $len = strlen($code);
        for ($i = 0; $i < $len; $i++) {
            $pos = strpos(self::CHARS, $code[$i]);
            if ($pos === false) {
                return null;
            }
            $num = $num * $base + $pos;
        }
        $num -= self::OFFSET;
        $id = $num ^ self::MASK;
        return ($id > 0 && $id < 2000000000) ? $id : null;
    }

    /**
     * Decode legacy base_convert / Knuth hash code (e.g. '1gr11cz' -> ID 20).
     */
    public static function decodeLegacyCode(string $code): ?int
    {
        try {
            $val = base_convert($code, 36, 10);
            if (!is_numeric($val)) {
                return null;
            }

            if (function_exists('bcmul') && function_exists('bcmod')) {
                $mult = bcmul((string)$val, '2654435761');
                $id = (int) bcmod($mult, '4294967296');
            } else {
                $id = (int) fmod((float)$val * 2654435761.0, 4294967296.0);
            }

            return ($id > 0 && $id < 10000000) ? $id : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Find DakungProdiFile model by code (supports new cipher, legacy code, and numeric ID).
     */
    public static function findByCode(?string $code): ?self
    {
        if (empty($code)) {
            return null;
        }

        // 1. Direct ID lookup
        if (is_numeric($code)) {
            $file = self::find($code);
            if ($file) {
                return $file;
            }
        }

        // 2. Decode with Base-57 cipher
        $id = self::decodeCode($code);
        if ($id) {
            $file = self::find($id);
            if ($file) {
                return $file;
            }
        }

        // 3. Decode legacy code (e.g. '1gr11cz')
        $legacyId = self::decodeLegacyCode($code);
        if ($legacyId) {
            $file = self::find($legacyId);
            if ($file) {
                return $file;
            }
        }

        return null;
    }

    const TYPE_DAKUNG_PRODI = 0;
    const TYPE_SK_PENDIRIAN_PRODI = 1;
    const TYPE_KEPUTUSAN_REKTOR = 2;
    const TYPE_SPMI = 3;
    const TYPE_SIKLUS_PPEPP = 4;
    const TYPE_STATUTA = 5;
    const TYPE_RENSTRA = 6;
    const TYPE_RIP = 7;
    const TYPE_RENOP = 8;
    const TYPE_SOTK = 9;
    const TYPE_KURIKULUM_PRODI = 10;
    const TYPE_LAPORAN_BENCHMARKING = 11;
    const TYPE_LAPORAN_EVALUASI_PPEPP = 12;
    const TYPE_PEDOMAN = 13;
    const TYPE_DIFERENSIASI_MISI = 14;

    public static function getModelClassByType(int $type): ?string
    {
        $models = [
            self::TYPE_DAKUNG_PRODI => DakungProdiFile::class,
            self::TYPE_SK_PENDIRIAN_PRODI => SkPendirianProdi::class,
            self::TYPE_KEPUTUSAN_REKTOR => DocumentKeputusanRektor::class,
            self::TYPE_SPMI => DocumentSpmi::class,
            self::TYPE_SIKLUS_PPEPP => DocumentSiklusPpepp::class,
            self::TYPE_STATUTA => DocumentStatutaUiiDalwa::class,
            self::TYPE_RENSTRA => DocumentRenstraUiiDalwa::class,
            self::TYPE_RIP => DocumentRip::class,
            self::TYPE_RENOP => DocumentRenopUiiDalwa::class,
            self::TYPE_SOTK => DocumentSotkUiiDalwa::class,
            self::TYPE_KURIKULUM_PRODI => DocumentKurikulumProdi::class,
            self::TYPE_LAPORAN_BENCHMARKING => DocumentLaporanBanchmarking::class,
            self::TYPE_LAPORAN_EVALUASI_PPEPP => DocumentLaporanEvaluasiPpepp::class,
            self::TYPE_PEDOMAN => DocumentPedoman::class,
            self::TYPE_DIFERENSIASI_MISI => DocumentDiferensiasiMisi::class,
        ];

        return $models[$type] ?? null;
    }

    // Separate unique character set for composite document codes (57 unique chars)
    // The original CHARS has duplicate characters (K, L, R) making it non-reversible for large values.
    // This set is guaranteed unique and used exclusively for document type+id encoding.
    private const DOC_CHARS = '7wKyN2xVbT4mQpRsZ9fGcLjhFdCe1u3t5i6o8gAaBDEHIJMOPUWXYnqvl';
    private const DOC_MASK = 0xA7E3F;
    private const DOC_OFFSET = 200000;

    /**
     * Encode an integer using the fixed DOC_CHARS character set (base-57, no duplicates).
     */
    private static function encodeDocCode(int $id): string
    {
        $num = ($id ^ self::DOC_MASK) + self::DOC_OFFSET;
        $base = strlen(self::DOC_CHARS);
        $code = '';
        while ($num > 0) {
            $code = self::DOC_CHARS[$num % $base] . $code;
            $num = intdiv($num, $base);
        }
        return $code ?: self::DOC_CHARS[0];
    }

    /**
     * Decode a code encoded with DOC_CHARS back to the original integer.
     */
    private static function decodeDocCode(string $code): ?int
    {
        $base = strlen(self::DOC_CHARS);
        $num = 0;
        $len = strlen($code);
        for ($i = 0; $i < $len; $i++) {
            $pos = strpos(self::DOC_CHARS, $code[$i]);
            if ($pos === false) {
                return null;
            }
            $num = $num * $base + $pos;
        }
        $num -= self::DOC_OFFSET;
        $id = $num ^ self::DOC_MASK;
        return ($id > 0 && $id < 2000000000) ? $id : null;
    }

    /**
     * Encode document model type and ID into a composite short code.
     * Uses the fixed DOC_CHARS to avoid the duplicate-character bug.
     */
    public static function encodeDocumentCode(int $type, int $id): string
    {
        $composite = (($type & 0x7F) << 24) | ($id & 0xFFFFFF);
        return self::encodeDocCode($composite);
    }

    /**
     * Decode composite short code to ['type' => int, 'id' => int].
     * Uses the fixed DOC_CHARS to avoid the duplicate-character bug.
     */
    public static function decodeDocumentCode(string $code): ?array
    {
        $composite = self::decodeDocCode($code);
        if (!$composite) {
            return null;
        }

        $type = ($composite >> 24) & 0x7F;
        $id = $composite & 0xFFFFFF;

        return ['type' => $type, 'id' => $id];
    }

    /**
     * Find any document (Dakung or Institution Document) by code.
     */
    public static function findDocumentByCode(?string $code): ?array
    {
        if (empty($code)) {
            return null;
        }

        // 1. Try decode as composite document code
        $decoded = self::decodeDocumentCode($code);
        if ($decoded) {
            $class = self::getModelClassByType($decoded['type']);
            if ($class) {
                $item = $class::find($decoded['id']);
                if ($item) {
                    return [
                        'type' => $decoded['type'],
                        'model' => $item,
                        'path' => $item->path ?? null,
                        'original_name' => $item->original_name ?? $item->nama ?? 'document.pdf',
                        'gdrive_file_id' => $item->gdrive_file_id ?? null,
                        'upload_status' => $item->upload_status ?? null,
                    ];
                }
            }
        }

        // 2. Fallback to legacy dakung file lookup
        $file = self::findByCode($code);
        if ($file) {
            return [
                'type' => self::TYPE_DAKUNG_PRODI,
                'model' => $file,
                'path' => $file->path,
                'original_name' => $file->original_name ?? $file->name,
                'gdrive_file_id' => $file->gdrive_file_id,
                'upload_status' => $file->upload_status,
            ];
        }

        return null;
    }

    public function getShortCodeAttribute()
    {
        return self::encodeCode($this->id);
    }

    public function getServerUrlAttribute()
    {
        return $this->path ? asset($this->path) : null;
    }

    public function getShortUrlAttribute()
    {
        return url('/s/' . $this->short_code);
    }
}
