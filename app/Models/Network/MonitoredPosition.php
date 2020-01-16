<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;

// A position monitored by the activity bot
class MonitoredPosition extends Model
{
    protected $fillable = [
        'id', 'identifier', 'callsign', 'staff_only'
    ];
}
