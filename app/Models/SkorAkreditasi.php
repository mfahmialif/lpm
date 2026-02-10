<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorAkreditasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'perguruan_tinggi',
        'prodi_id',
        'strata',
        'wilayah',
        'no_sk',
        'tgl_kadaluarsa',
        'peringkat',
        'tahun_sk',
        'status',
        'link_drive',
    ];

    protected $casts = [
        'tgl_kadaluarsa' => 'date',
    ];

    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class);
    }
}
