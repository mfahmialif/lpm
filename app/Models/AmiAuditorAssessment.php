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

    /**
     * Get the responses for this auditor assessment.
     */
    public function responses()
    {
        return $this->hasMany(AmiAssessmentResponse::class);
    }

    /**
     * Get the indicator for this auditor assessment.
     */
    public function indicator()
    {
        return $this->hasOne(AmiAssessmentIndicator::class);
    }

    /**
     * Get the selected scores for this auditor assessment.
     */
    public function selectedScores()
    {
        return $this->belongsToMany(AmiAssessmentScore::class, 'ami_assessment_selections');
    }
}
