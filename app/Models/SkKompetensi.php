<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkKompetensi extends Model
{
    use HasFactory;

    protected $table = 'sk_kompetensi';

    protected $guarded = [];

    protected $casts = [
        'tanggal_sk' => 'date',
        'is_active' => 'boolean',
    ];
}
