<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['url_image', 'published_at_formatted', 'url']; // supaya otomatis ikut tampil
    protected $casts   = [
        'published_at' => 'datetime',
    ];

    public function getUrlAttribute()
    {
        return route('news.detail', ['slug' => $this->slug]);
    }

    public function getPublishedAtFormattedAttribute()
    {
        return $this->published_at->format('d F Y');
    }

    public function getUrlImageAttribute()
    {
        if ($this->image) {
            return asset('storage/image-news/' . $this->image);
        }

        // fallback jika tidak ada gambar
        return asset('home/assets/img/blog/1.jpg');
    }

    public function newsLike()
    {
        return $this->hasMany(NewsLike::class);
    }

    public function newsCategory()
    {
        return $this->hasMany(NewsCategory::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'news_categories', 'news_id', 'category_id');
    }

    public function newsComment()
    {
        return $this->hasMany(NewsComments::class);
    }
}
