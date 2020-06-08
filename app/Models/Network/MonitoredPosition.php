<?php

namespace App\Models\Network;

use App\Jobs\ProcessRosterInactivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// A position monitored by the activity bot
class MonitoredPosition extends Model
{
    use LogsActivity;

    protected $fillable = [
        'id', 'identifier', 'staff_only', 'polygon_coordinates'
    ];

    public function lastSession()
    {
        $session = SessionLog::where('callsign', $this->identifier)->get()->last();
        if (!$session) return null;
        return $session;
    }

    public function sessions()
    {
        $sessions =  SessionLog::where('callsign', $this->identifier)->get();
        return $sessions;
    }

    public function lastOnlinePretty()
    {
        $session = $this->lastSession();
        if (!$session) return "Never used";
        return Carbon::create($session->session_end)->diffForHumans();
    }
}
