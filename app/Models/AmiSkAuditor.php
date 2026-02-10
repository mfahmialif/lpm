<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSkAuditor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_periode_id',
        'unit_id',
        'nomor_sk',
        'tanggal_sk',
        'auditor_ketua_id',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
    ];

    // === Relationships ===

    public function periode()
    {
        return $this->belongsTo(AmiPeriode::class, 'ami_periode_id');
    }

    public function unit()
    {
        return $this->belongsTo(AmiUnitAudit::class, 'unit_id');
    }

    public function indikators()
    {
        return $this->hasMany(AmiIndikator::class)->orderBy('urutan');
    }

    public function ketuaAuditor()
    {
        return $this->belongsTo(User::class, 'auditor_ketua_id');
    }

    public function anggotas()
    {
        return $this->hasMany(AmiSkAuditorAnggota::class);
    }

    public function auditorAnggotas()
    {
        return $this->hasMany(AmiSkAuditorAnggota::class)->where('peran', 'auditor_anggota');
    }

    public function auditees()
    {
        return $this->hasMany(AmiSkAuditorAnggota::class)->where('peran', 'auditee');
    }

    public function evaluasiDiris()
    {
        return $this->hasMany(AmiEvaluasiDiri::class);
    }

    public function asesmens()
    {
        return $this->hasMany(AmiAsesmen::class);
    }

    public function temuanAudits()
    {
        return $this->hasMany(AmiTemuanAudit::class);
    }

    public function laporanKinerjas()
    {
        return $this->hasMany(AmiLaporanKinerja::class);
    }

    // === Helpers ===

    public function isKetuaAuditor($userId): bool
    {
        return $this->auditor_ketua_id == $userId;
    }

    public function isAuditorAnggota($userId): bool
    {
        return $this->anggotas()
            ->where('user_id', $userId)
            ->where('peran', 'auditor_anggota')
            ->exists();
    }

    public function isAuditor($userId): bool
    {
        return $this->isKetuaAuditor($userId) || $this->isAuditorAnggota($userId);
    }

    public function isAuditee($userId): bool
    {
        return $this->anggotas()
            ->where('user_id', $userId)
            ->where('peran', 'auditee')
            ->exists();
    }
}
