<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

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

    public function activityUnit()
    {
        return $this->hasMany(ActivityUnit::class,);
    }

    public function activity()
    {
        return $this->belongsToMany(Activity::class, 'activity_unit', 'activity_id', 'unit_id');
    }
}
