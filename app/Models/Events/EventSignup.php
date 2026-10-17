<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignup extends Model
{
    protected $table = 'event_signups';
    protected $fillable = ['full_name', 'cid', 'preferred_position', 'availability', 'submitted_at'];
    protected $casts = ['availability' => 'array', 'submitted_at' => 'datetime'];
}
