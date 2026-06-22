<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_rip';

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
     * Get the unit that owns the document.
     */
    public function unit()
    {
        return $this->belongsTo(UnitDokument::class, 'unit_id');
    }

    /**
     * Get the prodi that owns the document.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
