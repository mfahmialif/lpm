<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiFindingResult extends Model
{
    use HasFactory;

    protected $table = 'ami_finding_results';

    protected $guarded = [];

    /**
     * Get the category that owns the finding result.
     */
    public function category()
    {
        return $this->belongsTo(AmiCategory::class, 'category_id');
    }

    /**
     * Get the prodi that owns the finding result.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
