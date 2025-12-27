<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmiAssessmentScore extends Model
{
    protected $fillable = [
        'ami_assessment_indicator_id',
        'score',
        'description',
    ];

    public function indicator()
    {
        return $this->belongsTo(AmiAssessmentIndicator::class, 'ami_assessment_indicator_id');
    }
}
