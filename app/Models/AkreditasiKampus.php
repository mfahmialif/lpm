<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkreditasiKampus extends Model
{
    use HasFactory;

    protected $table = 'akreditasi_kampus';

    protected $fillable = [
        'perguruan_tinggi',
        'akreditasi',
        'tanggal_sk',
        'peringkat',
        'kadaluarsa',
        'status',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'kadaluarsa' => 'date',
    ];
}
