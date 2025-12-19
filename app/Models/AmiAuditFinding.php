<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAuditFinding extends Model
{
    use HasFactory;

    protected $table = 'ami_audit_findings';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the audit finding.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    public function prodiUnit()
    {
        return $this->belongsTo(ProdiUnit::class);
    }
}
