<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiEvaluasiDiriFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_evaluasi_diri_id',
        'file_path',
        'file_name',
    ];

    public function evaluasiDiri()
    {
        return $this->belongsTo(AmiEvaluasiDiri::class, 'ami_evaluasi_diri_id');
    }
}
