<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSelfAssessmentResponse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assessment()
    {
        return $this->belongsTo(AmiSelfAssessment::class, 'ami_self_assessment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
