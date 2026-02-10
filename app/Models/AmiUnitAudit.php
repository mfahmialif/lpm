<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiUnitAudit extends Model
{
    use HasFactory;

    protected $table = 'ami_unit_audits';

    protected $fillable = [
        'nama',
        'kode',
        'jenis',
        'keterangan',
    ];

    // === Relationships ===

    public function skAuditors()
    {
        return $this->hasMany(AmiSkAuditor::class, 'unit_id');
    }
}
