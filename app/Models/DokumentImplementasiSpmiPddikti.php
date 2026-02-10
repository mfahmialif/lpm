<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentImplementasiSpmiPddikti extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokument_implementasi_spmi_pddikti';

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
        'status' => 'string',
    ];

    /**
     * Get the prodi that owns the document.
     */
    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class, 'prodi_id');
    }
}
