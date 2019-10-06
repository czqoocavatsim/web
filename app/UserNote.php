<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
{
    protected $fillable = [
        'user_id', 'author', 'content', 'confidential', 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
