<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiRtm extends Model
{
    use HasFactory;

    protected $table = 'ami_rtms';

    protected $fillable = [
        'kode_rtm',
        'tanggal_rtm',
        'pimpinan_id',
        'status',
        'catatan_umum',
        'created_by',
    ];

    protected $casts = [
        'tanggal_rtm' => 'date',
    ];

    // === Relationships ===

    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rtmSks()
    {
        return $this->hasMany(AmiRtmSk::class, 'ami_rtm_id');
    }

    public function skAuditors()
    {
        return $this->belongsToMany(AmiSkAuditor::class, 'ami_rtm_sks', 'ami_rtm_id', 'ami_sk_auditor_id')
            ->withTimestamps();
    }

    public function rtmTemuans()
    {
        return $this->hasMany(AmiRtmTemuan::class, 'ami_rtm_id');
    }

    // === Helpers ===

    public function canBeSahkan(): bool
    {
        // All temuan must have keputusan, PIC, and target
        return $this->rtmTemuans->every(function ($t) {
            return $t->keputusan
                && $t->penanggung_jawab_id
                && $t->target_selesai;
        });
    }

    public function canBeDitutup(): bool
    {
        // No open tindak lanjut
        return $this->rtmTemuans->every(function ($t) {
            return $t->status_tindak_lanjut !== 'open';
        });
    }
}
