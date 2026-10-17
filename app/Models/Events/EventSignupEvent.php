<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignupEvent extends Model
{
    protected $table = 'event_signup_events';
    protected $fillable = ['name', 'event_date', 'starts_at', 'ends_at', 'staffing_requirements', 'roster_published', 'is_open', 'sort_order'];
    protected $casts = [
        'event_date' => 'date',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'staffing_requirements' => 'array',
        'roster_published' => 'boolean',
        'is_open' => 'boolean',
    ];

    public function slots()
    {
        return $this->hasMany(EventSignupSlot::class, 'event_id');
    }

    public function signups()
    {
        return $this->hasMany(EventSignup::class, 'event_id');
    }

    public function positions()
    {
        return $this->hasMany(EventSignupPosition::class, 'event_id')->orderBy('sort_order')->orderBy('id');
    }

    public function assignments()
    {
        return $this->hasMany(EventSignupAssignment::class, 'event_id');
    }
}
