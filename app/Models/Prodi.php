<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the accreditations for this prodi.
     */
    public function accreditations()
    {
        return $this->hasMany(Accreditation::class);
    }
}
