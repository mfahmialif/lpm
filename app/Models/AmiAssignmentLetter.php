<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAssignmentLetter extends Model
{
    use HasFactory;

    protected $table = 'ami_assignment_letters';

    protected $guarded = [];

    protected $casts = [
        'assignment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the AMI period that owns the assignment letter.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the assignment letter.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
