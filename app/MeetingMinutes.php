<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingMinutes extends Model
{
    protected $fillable = [
        'user_id', 'title', 'link'
    ];
}
