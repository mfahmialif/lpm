<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function newsCategory()
    {
        return $this->hasMany(NewsCategory::class, );
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_categories', 'category_id', 'news_id');
    }
}
