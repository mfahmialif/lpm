<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAssessmentResponse extends Model
{
    use HasFactory;

    protected $table = 'ami_assessment_responses';

    protected $guarded = [];

    /**
     * Get the AMI auditor assessment that owns this response.
     */
    public function amiAuditorAssessment()
    {
        return $this->belongsTo(AmiAuditorAssessment::class);
    }

    /**
     * Get the user who sent this response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
