<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DakungProdiFile extends Model
{
    protected $table = 'dakung_prodi_files';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(DakungProdiCategory::class, 'dakung_prodi_category_id');
    }
}
