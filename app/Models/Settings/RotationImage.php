<?php

namespace App\Models\Settings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class RotationImage extends Model
{
    protected $fillable = [
        'user_id', 'path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
