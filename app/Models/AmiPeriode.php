<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiPeriode extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_selesai',
        'status',
        'deskripsi',
    ];

    public function skAuditors()
    {
        return $this->hasMany(AmiSkAuditor::class);
    }
}
