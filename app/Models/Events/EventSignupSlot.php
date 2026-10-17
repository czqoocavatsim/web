<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventSignupSlot extends Model
{
    protected $table = 'event_signup_slots';
    protected $fillable = ['label', 'is_open', 'sort_order'];
    protected $casts = ['is_open' => 'boolean'];
}
