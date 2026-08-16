<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DakungProdiCategory extends Model
{
    protected $table = 'dakung_prodi_categories';
    protected $guarded = [];

    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class);
    }

    public function files()
    {
        return $this->hasMany(DakungProdiFile::class, 'dakung_prodi_category_id')->orderBy('created_at', 'desc');
    }
}
