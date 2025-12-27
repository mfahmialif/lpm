<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmiAssessmentIndicator extends Model
{
    protected $fillable = [
        'ami_auditor_assessment_id',
        'indicator',
    ];

    public function amiAuditorAssessment()
    {
        return $this->belongsTo(AmiAuditorAssessment::class);
    }

    public function scores()
    {
        return $this->hasMany(AmiAssessmentScore::class)->orderBy('score');
    }
}
