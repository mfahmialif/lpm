<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
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
        'order_index' => 'integer',
    ];

    /**
     * Get the accreditation that owns the requirement.
     */
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class);
    }

    /**
     * Get the parent requirement.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the child requirements.
     */
    public function children()
    {
        return $this->hasMany(Requirement::class, 'parent_id')
            ->orderBy('code', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
