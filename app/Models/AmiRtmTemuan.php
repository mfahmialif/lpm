<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiRtmTemuan extends Model
{
    use HasFactory;

    protected $table = 'ami_rtm_temuans';

    protected $fillable = [
        'ami_rtm_id',
        'ami_sk_auditor_id',
        'ami_hasil_temuan_id',
        'keputusan',
        'rencana_tindak_lanjut',
        'penanggung_jawab_id',
        'target_selesai',
        'status_tindak_lanjut',
    ];

    protected $casts = [
        'target_selesai' => 'date',
    ];

    public function rtm()
    {
        return $this->belongsTo(AmiRtm::class, 'ami_rtm_id');
    }

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function hasilTemuan()
    {
        return $this->belongsTo(AmiHasilTemuan::class, 'ami_hasil_temuan_id');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
}
