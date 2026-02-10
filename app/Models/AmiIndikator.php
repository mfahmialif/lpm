<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiIndikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_sk_auditor_id',
        'kode',
        'pertanyaan',
        'narasi_evaluasi_diri',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function rubrikSkors()
    {
        return $this->hasMany(AmiRubrikSkor::class)->orderBy('skor');
    }

    public function evaluasiDiris()
    {
        return $this->hasMany(AmiEvaluasiDiri::class);
    }

    public function asesmens()
    {
        return $this->hasMany(AmiAsesmen::class);
    }
}
