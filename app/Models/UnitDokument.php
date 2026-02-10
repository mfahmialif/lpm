<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitDokument extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units_dokument';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'jenis' => 'string',
        'jenjang' => 'string',
    ];

    /**
     * Get all documents renstra for this unit.
     */
    public function documentRenstraUiiDalwa()
    {
        return $this->hasMany(DocumentRenstraUiiDalwa::class, 'unit_id');
    }

    /**
     * Get all documents renop for this unit.
     */
    public function documentRenopUiiDalwa()
    {
        return $this->hasMany(DocumentRenopUiiDalwa::class, 'unit_id');
    }
}
