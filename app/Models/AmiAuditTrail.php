<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmiAuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'data_before',
        'data_after',
        'ip_address',
    ];

    protected $casts = [
        'data_before' => 'json',
        'data_after' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
