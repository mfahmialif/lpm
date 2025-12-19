<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAuditorAssessment extends Model
{
    use HasFactory;

    protected $table = 'ami_auditor_assessments';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the auditor assessment.
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
