<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
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
            return asset('storage/image-activity/' . $this->image);
        }

        // fallback jika tidak ada gambar
        return asset('home/assets/img/blog/1.jpg');
    }

    public function activityUnit()
    {
        return $this->hasMany(ActivityUnit::class);
    }
    public function activityTag()
    {
        return $this->hasMany(ActivityTag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsToMany(Unit::class, 'activity_unit', 'activity_id', 'unit_id');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'activity_tag', 'activity_id', 'tag_id');
    }

    public function document(){
        return $this->hasMany(ActivityDocument::class);
    }
}
