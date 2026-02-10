<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAsesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_sk_auditor_id',
        'ami_indikator_id',
        'skor_pilihan',
        'catatan_asesor',
        'assessed_by',
        'is_final',
        'finalized_at',
        'finalized_by',
    ];

    protected $casts = [
        'is_final' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function indikator()
    {
        return $this->belongsTo(AmiIndikator::class, 'ami_indikator_id');
    }

    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
