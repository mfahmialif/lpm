<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiHasilTemuan extends Model
{
    use HasFactory;

    protected $table = 'ami_hasil_temuans';

    protected $fillable = [
        'ami_sk_auditor_id',
        'judul',
        'ringkasan',
        'kategori',
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

    public function details()
    {
        return $this->hasMany(AmiHasilTemuanDetail::class, 'ami_hasil_temuan_id');
    }

    public function temuanAudits()
    {
        return $this->belongsToMany(
            AmiTemuanAudit::class,
            'ami_hasil_temuan_details',
            'ami_hasil_temuan_id',
            'ami_temuan_audit_id'
        )->withTimestamps();
    }
}
