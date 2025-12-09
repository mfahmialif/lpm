<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSelfAssessment extends Model
{
    use HasFactory;

    protected $table = 'ami_self_assessments';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the self assessment.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the self assessment.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
