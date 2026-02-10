<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiTemuanAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_sk_auditor_id',
        'jenis_temuan',
        'deskripsi',
        'rekomendasi',
        'created_by',
    ];

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
