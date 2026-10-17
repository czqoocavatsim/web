<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignupAssignment extends Model
{
    protected $table = 'event_signup_assignments';
    protected $fillable = ['event_id', 'signup_id', 'position_id', 'starts_at', 'ends_at'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function signup()
    {
        return $this->belongsTo(EventSignup::class, 'signup_id');
    }

    public function position()
    {
        return $this->belongsTo(EventSignupPosition::class, 'position_id');
    }
}
