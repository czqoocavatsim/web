<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignupPosition extends Model
{
    protected $table = 'event_signup_positions';
    protected $fillable = ['event_id', 'sector', 'callsign', 'is_open', 'sort_order'];
    protected $casts = ['is_open' => 'boolean'];

    public function event()
    {
        return $this->belongsTo(EventSignupEvent::class, 'event_id');
    }
}
