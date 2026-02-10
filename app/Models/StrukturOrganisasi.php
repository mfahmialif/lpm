<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_lpm_id',
        'penasehat',
        'penanggung_jawab',
        'ketua_lpm',
        'anggota',
        'kjm_pasca_sarjana',
        'gjm_prodi_mpi_s2',
        'gjm_prodi_pai_s2',
        'gjm_prodi_pba_s2',
        'gjm_prodi_pai_s3',
        'gjm_prodi_pba_s3',
        'kjm_fakultas_syariah',
        'gjm_prodi_hki',
        'gjm_prodi_esy',
        'kjm_fakultas_tarbiyah',
        'gjm_prodi_pai',
        'gjm_prodi_pba',
        'gjm_prodi_mpi',
        'kjm_fakultas_dakwah',
        'gjm_prodi_kpi',
        'gjm_prodi_bki',
        'gjm_prodi_mhu',
        'kjm_fakultas_adab',
        'gjm_prodi_spi',
    ];

    public function periodeLpm()
    {
        return $this->belongsTo(PeriodeLpm::class);
    }

    public function anggotaRelasi()
    {
        return $this->hasMany(AnggotaStrukturOrganisasi::class);
    }
}
