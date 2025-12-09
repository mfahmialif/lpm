<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSelfEvaluation extends Model
{
    use HasFactory;

    protected $table = 'ami_self_evaluations';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the self evaluation.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the self evaluation.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
