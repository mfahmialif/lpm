<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiPerformanceReport extends Model
{
    use HasFactory;

    protected $table = 'ami_performance_reports';

    protected $guarded = [];

    protected $casts = [
        'report_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the AMI period that owns the performance report.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the performance report.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
