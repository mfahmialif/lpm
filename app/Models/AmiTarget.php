<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmiTarget extends Model
{
    protected $table = 'ami_targets';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the target.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the target.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
