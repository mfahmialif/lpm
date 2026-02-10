<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiEvaluasiDiri extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_sk_auditor_id',
        'ami_indikator_id',
        'jawaban',
        'submitted_by',
    ];

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function indikator()
    {
        return $this->belongsTo(AmiIndikator::class, 'ami_indikator_id');
    }

    public function files()
    {
        return $this->hasMany(AmiEvaluasiDiriFile::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get status warna indikator
     * hijau: jawaban + dokumen lengkap
     * kuning: jawaban ada, dokumen belum
     * merah: belum dijawab
     */
    public function getStatusAttribute(): string
    {
        if (empty($this->jawaban)) {
            return 'merah';
        }

        if ($this->files()->count() === 0) {
            return 'kuning';
        }

        return 'hijau';
    }
}
