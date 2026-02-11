<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiTargetAmi extends Model
{
    use HasFactory;

    protected $table = 'ami_target_amis';

    protected $fillable = [
        'ami_sk_auditor_id',
        'kode_target',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'standar_acuan',
        'ruang_lingkup',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // === Relationships ===

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // === Helpers ===

    public function canBeAktifkan(): bool
    {
        return $this->status === 'draft'
            && $this->tanggal_mulai
            && $this->tanggal_selesai
            && $this->tanggal_selesai->gte($this->tanggal_mulai);
    }

    public function canBeDitutup(): bool
    {
        return $this->status === 'aktif';
    }

    public function canBeDeleted(): bool
    {
        return true; 
    }
}
