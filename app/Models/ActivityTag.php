<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTag extends Model
{
    use HasFactory;

    protected $table   = 'activity_tag';
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
