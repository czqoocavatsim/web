<?php

namespace App\Models\Network;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// A position monitored by the activity bot
class MonthlyHours extends Model
{
    protected $fillable = [
        'id', 'identifier', 'callsign', 'staff_only', 'polygon_coordinates'
    ];

    public function lastSession()
    {
        $session = SessionLog::where('callsign', $this->identifier)->get()->last();
        if (!$session) return null;
        return $session;
    }
    
}
