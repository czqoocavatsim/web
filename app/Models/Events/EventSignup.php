<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignup extends Model
{
    protected $table = 'event_signups';
    protected $fillable = ['event_id', 'full_name', 'cid', 'preferred_position', 'availability', 'availability_windows', 'submitted_at'];
    protected $casts = ['availability' => 'array', 'availability_windows' => 'array', 'submitted_at' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(EventSignupEvent::class, 'event_id');
    }

    public function assignments()
    {
        return $this->hasMany(EventSignupAssignment::class, 'signup_id');
    }
}
