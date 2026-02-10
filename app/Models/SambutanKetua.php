<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SambutanKetua extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ketua',
        'foto',
        'sambutan',
    ];
}
