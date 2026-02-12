<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAsesmenFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_asesmen_id',
        'file_path',
        'file_name',
    ];

    public function asesmen()
    {
        return $this->belongsTo(AmiAsesmen::class, 'ami_asesmen_id');
    }
}
