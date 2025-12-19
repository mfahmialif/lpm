<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiFinalResult extends Model
{
    use HasFactory;

    protected $table = 'ami_final_results';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the final result.
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
