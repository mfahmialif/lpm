<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiSkAuditorAnggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_sk_auditor_id',
        'user_id',
        'peran',
    ];

    public function skAuditor()
    {
        return $this->belongsTo(AmiSkAuditor::class, 'ami_sk_auditor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
