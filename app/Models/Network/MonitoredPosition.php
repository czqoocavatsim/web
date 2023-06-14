<?php

namespace App\Models\Network;

use Carbon\Carbon;
use App\Models\Network\SessionLog;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// A position monitored by the activity bot
class MonitoredPosition extends Model
{
    use LogsActivity;

    protected $fillable = [
        'id', 'identifier', 'staff_only', 'polygon_coordinates',
    ];

    public function sessions()
    {
        return $this->hasMany(SessionLog::class);
    }

    public function lastOnline()
    {
        $session = $this->sessions->last();
        if (!$session) {
            return null;
        }

        return Carbon::create($session->session_end);
    }

    public function activeSession()
    {
        if ($session = $this->sessions->where('session_end', null)->first()) {
            return $session;
        } else {
            return null;
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
