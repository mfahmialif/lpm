<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProdiUnit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_prodi_unit';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prodi unit.
     */
    public function prodiUnit()
    {
        return $this->belongsTo(ProdiUnit::class);
    }
}
