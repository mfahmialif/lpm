<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiRtmSk extends Model
{
    use HasFactory;

    protected $table = 'ami_rtm_sks';

    protected $fillable = [
        'ami_rtm_id',
        'ami_sk_auditor_id',
    ];

    public function rtm()
    {
        return $this->belongsTo(AmiRtm::class, 'ami_rtm_id');
    }

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }
}
