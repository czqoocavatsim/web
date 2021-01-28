<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// A position monitored by the activity bot
class MonthlyHours extends Model
{
    use LogsActivity;

    protected $fillable = [
        'id', 'identifier', 'callsign', 'staff_only', 'polygon_coordinates',
    ];

    public function lastSession()
    {
        $session = SessionLog::where('callsign', $this->identifier)->get()->last();
        if (!$session) {
            return null;
        }

        return $session;
    }
}
