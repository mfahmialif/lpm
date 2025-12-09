<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAuditorDecree extends Model
{
    use HasFactory;

    protected $table = 'ami_auditor_decrees';

    protected $guarded = [];

    protected $casts = [
        'decree_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the AMI period that owns the auditor decree.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the auditor decree.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
