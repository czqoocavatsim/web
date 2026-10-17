<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignupEvent extends Model
{
    protected $table = 'event_signup_events';
    protected $fillable = ['name', 'event_date', 'is_open', 'sort_order'];
    protected $casts = ['event_date' => 'date', 'is_open' => 'boolean'];

    public function slots()
    {
        return $this->hasMany(EventSignupSlot::class, 'event_id');
    }

    public function signups()
    {
        return $this->hasMany(EventSignup::class, 'event_id');
    }
}
