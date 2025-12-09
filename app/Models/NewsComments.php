<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComments extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['comment_date'];

    public function getCommentDateAttribute()
    {
        return $this->updated_at->format('d F Y');
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function parent()
    {
        return $this->belongsTo(NewsComments::class, 'parent_comment_id');
    }

    public function replies()
    {
        return $this->hasMany(NewsComments::class, 'parent_comment_id');
    }
}
