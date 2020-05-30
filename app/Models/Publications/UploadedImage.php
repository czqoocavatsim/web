<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
