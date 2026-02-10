<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdiUnit extends Model
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

    /**
     * Get the users associated with this prodi unit.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_prodi_unit')
            ->withTimestamps();
    }
}
