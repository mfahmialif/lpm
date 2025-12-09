<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiRtm extends Model
{
    use HasFactory;

    protected $table = 'ami_rtm';

    protected $guarded = [];

    /**
     * Get the AMI period that owns the RTM.
     */
    public function amiPeriod()
    {
        return $this->belongsTo(AmiPeriod::class);
    }

    /**
     * Get the prodi that owns the RTM.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
