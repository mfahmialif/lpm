<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function activityTags()
    {
        return $this->hasMany(ActivityTags::class, );
    }

    public function activity()
    {
        return $this->belongsToMany(Activity::class, 'activity_tags', 'activity_id', 'tag_id');
    }
}
