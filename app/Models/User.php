<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'photo',
        'telp',
        'affiliate',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['url_photo'];

    public function getUrlPhotoAttribute()
    {
        if ($this->photo) {
            return asset('photo/' . $this->photo);
        }

        // fallback jika tidak ada gambar
        return asset('home/assets/img/favicon.png');
    }

    public function player()
    {
        return $this->hasOne(Player::class);
    }
}
