<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiCategory extends Model
{
    use HasFactory;

    protected $table = 'ami_categories';

    protected $guarded = [];

    /**
     * Get the finding results for this category.
     */
    public function findingResults()
    {
        return $this->hasMany(AmiFindingResult::class, 'category_id');
    }
}
