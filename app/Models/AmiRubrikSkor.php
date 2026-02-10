<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiRubrikSkor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_indikator_id',
        'skor',
        'deskripsi',
    ];

    public function indikator()
    {
        return $this->belongsTo(AmiIndikator::class, 'ami_indikator_id');
    }
}
