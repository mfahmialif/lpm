<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSelfAssessmentScore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function indicator()
    {
        return $this->belongsTo(AmiSelfAssessmentIndicator::class, 'ami_self_assessment_indicator_id');
    }
}
