<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiActivity extends Model
{
    use HasFactory;

    protected $table = 'ami_activities';

    protected $guarded = [];

    protected $casts = [
        'filling_date' => 'date',
        'assessment_date' => 'date',
    ];

    /**
     * Get the AMI period that owns the activity.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the activity.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
