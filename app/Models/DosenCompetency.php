<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenCompetency extends Model
{
    use HasFactory;

    protected $table = 'dosen_competencies';

    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function prodiCompetency()
    {
        return $this->belongsTo(ProdiCompetency::class, 'prodi_competency_id');
    }

    // Karena prodi_competency adalah pivot tanpa model dedicated (kecuali kita buat), 
    // kita bisa akses prodi dan competency lewat relasi pivot, atau kita buat model khusus untuk pivot ini.
    // Opsi terbaik: Buat model Pivot ProdiCompetency agar relasi lebih rapi.

    public function periodeAkademik()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_akademik_id');
    }

    public function skKompetensi()
    {
        return $this->belongsTo(SkKompetensi::class, 'sk_kompetensi_id');
    }
}
