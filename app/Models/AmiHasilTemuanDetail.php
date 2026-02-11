<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiHasilTemuanDetail extends Model
{
    use HasFactory;

    protected $table = 'ami_hasil_temuan_details';

    protected $fillable = [
        'ami_hasil_temuan_id',
        'ami_temuan_audit_id',
    ];

    public function hasilTemuan()
    {
        return $this->belongsTo(AmiHasilTemuan::class, 'ami_hasil_temuan_id');
    }

    public function temuanAudit()
    {
        return $this->belongsTo(AmiTemuanAudit::class, 'ami_temuan_audit_id');
    }
}
