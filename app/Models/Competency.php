<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $table = 'competencies';

    protected $guarded = [];

    public function prodis()
    {
        return $this->belongsToMany(Prodi::class, 'prodi_competencies', 'competency_id', 'prodi_id')
            ->withTimestamps();
    }
}
