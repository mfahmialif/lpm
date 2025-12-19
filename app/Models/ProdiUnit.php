<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdiUnit extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the accreditations for this prodi.
     */
    public function accreditations()
    {
        return $this->hasMany(Accreditation::class);
    }

    /**
     * Get the AMI targets for this prodi.
     */
    public function amiTargets()
    {
        return $this->hasMany(AmiTarget::class);
    }

    /**
     * Get the AMI activities for this prodi.
     */
    public function amiActivities()
    {
        return $this->hasMany(AmiActivity::class);
    }

    /**
     * Get the AMI auditor decrees for this prodi.
     */
    public function amiAuditorDecrees()
    {
        return $this->hasMany(AmiAuditorDecree::class);
    }

    /**
     * Get the AMI assignment letters for this prodi.
     */
    public function amiAssignmentLetters()
    {
        return $this->hasMany(AmiAssignmentLetter::class);
    }

    /**
     * Get the AMI performance reports for this prodi.
     */
    public function amiPerformanceReports()
    {
        return $this->hasMany(AmiPerformanceReport::class);
    }

    /**
     * Get the AMI self evaluations for this prodi.
     */
    public function amiSelfEvaluations()
    {
        return $this->hasMany(AmiSelfEvaluation::class);
    }

    /**
     * Get the AMI self assessments for this prodi.
     */
    public function amiSelfAssessments()
    {
        return $this->hasMany(AmiSelfAssessment::class);
    }

    /**
     * Get the AMI auditor assessments for this prodi.
     */
    public function amiAuditorAssessments()
    {
        return $this->hasMany(AmiAuditorAssessment::class);
    }

    /**
     * Get the AMI audit findings for this prodi.
     */
    public function amiAuditFindings()
    {
        return $this->hasMany(AmiAuditFinding::class);
    }

    /**
     * Get the AMI finding results for this prodi.
     */
    public function amiFindingResults()
    {
        return $this->hasMany(AmiFindingResult::class);
    }

    /**
     * Get the AMI RTM for this prodi.
     */
    public function amiRtm()
    {
        return $this->hasMany(AmiRtm::class);
    }

    /**
     * Get the AMI official reports for this prodi.
     */
    public function amiOfficialReports()
    {
        return $this->hasMany(AmiOfficialReport::class);
    }

    /**
     * Get the AMI final results for this prodi.
     */
    public function amiFinalResults()
    {
        return $this->hasMany(AmiFinalResult::class);
    }
}
