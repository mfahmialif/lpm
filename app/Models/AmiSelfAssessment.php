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

    public function prodiUnit()
    {
        return $this->belongsTo(ProdiUnit::class);
    }

    /**
     * Get the responses for this self assessment.
     */
    public function responses()
    {
        return $this->hasMany(AmiSelfAssessmentResponse::class);
    }

    /**
     * Get the indicator for this self assessment.
     */
    public function indicator()
    {
        return $this->hasOne(AmiSelfAssessmentIndicator::class);
    }

    /**
     * Get the selected scores for this self assessment.
     */
    public function selectedScores()
    {
        return $this->belongsToMany(AmiSelfAssessmentScore::class, 'ami_self_assessment_selections', 'ami_self_assessment_id', 'ami_self_assessment_score_id');
    }
}
