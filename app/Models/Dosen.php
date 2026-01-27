<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'mst_dosen';

    protected $fillable = [
        'prodi_id',
        'jk_id',
        'kota_id',
        'dosen_status_id',
        'status_dosen_tetap_id',
        'user_id',
        'kode',
        'nidn',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'hp',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
