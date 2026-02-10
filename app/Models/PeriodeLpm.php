<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeLpm extends Model
{
    use HasFactory;

    protected $fillable = [
        'dari',
        'sampai',
        'status',
    ];

    protected $casts = [
        'dari' => 'date',
        'sampai' => 'date',
    ];
}
