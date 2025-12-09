<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmiPeriod extends Model
{
    protected $table = 'ami_periods';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the AMI targets for this period.
     */
    public function amiTargets()
    {
        return $this->hasMany(AmiTarget::class);
    }

    /**
     * Get the AMI activities for this period.
     */
    public function amiActivities()
    {
        return $this->hasMany(AmiActivity::class);
    }

    /**
     * Get the AMI auditor decrees for this period.
     */
    public function amiAuditorDecrees()
    {
        return $this->hasMany(AmiAuditorDecree::class);
    }

    /**
     * Get the AMI assignment letters for this period.
     */
    public function amiAssignmentLetters()
    {
        return $this->hasMany(AmiAssignmentLetter::class);
    }

    /**
     * Get the AMI performance reports for this period.
     */
    public function amiPerformanceReports()
    {
        return $this->hasMany(AmiPerformanceReport::class);
    }

    /**
     * Get the AMI self evaluations for this period.
     */
    public function amiSelfEvaluations()
    {
        return $this->hasMany(AmiSelfEvaluation::class);
    }

    /**
     * Get the AMI self assessments for this period.
     */
    public function amiSelfAssessments()
    {
        return $this->hasMany(AmiSelfAssessment::class);
    }

    /**
     * Get the AMI auditor assessments for this period.
     */
    public function amiAuditorAssessments()
    {
        return $this->hasMany(AmiAuditorAssessment::class);
    }

    /**
     * Get the AMI audit findings for this period.
     */
    public function amiAuditFindings()
    {
        return $this->hasMany(AmiAuditFinding::class);
    }

    /**
     * Get the AMI RTM for this period.
     */
    public function amiRtm()
    {
        return $this->hasMany(AmiRtm::class);
    }

    /**
     * Get the AMI official reports for this period.
     */
    public function amiOfficialReports()
    {
        return $this->hasMany(AmiOfficialReport::class);
    }

    /**
     * Get the AMI final results for this period.
     */
    public function amiFinalResults()
    {
        return $this->hasMany(AmiFinalResult::class);
    }
}
