<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiOfficialReport extends Model
{
    use HasFactory;

    protected $table = 'ami_official_reports';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the official report.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    public function prodiUnit()
    {
        return $this->belongsTo(ProdiUnit::class);
    }
}
