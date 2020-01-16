<?php

namespace App\Models\Network;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Log of all sessions
class SessionLog extends Model
{
    // session_start and session_end are unix timestamps
    protected $fillable = [
      'id', 'user_id', 'cid', 'session_start', 'session_end', 'callsign', 'session_length', 'is_new'
    ];

    public function user() {
        $this->belongsTo(User::class);
    }
}
