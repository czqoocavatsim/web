<?php

namespace App\Models\Network;

use Spatie\Activitylog\LogOptions;
use App\Models\Roster\RosterMember;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// Log of all sessions
/**
 * App\Models\Network\SessionLog
 *
 * @property int $id
 * @property int $cid
 * @property \Illuminate\Support\Carbon $session_start
 * @property \Illuminate\Support\Carbon|null $session_end
 * @property float|null $duration
 * @property int $emails_sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $monitored_position_id
 * @property int|null $roster_member_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Network\MonitoredPosition|null $position
 * @property-read RosterMember|null $rosterMember
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereEmailsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereMonitoredPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereRosterMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereSessionEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereSessionStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SessionLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SessionLog extends Model
{
    use LogsActivity;

    // session_start and session_end are in format 'Y-m-d H:i:s'
    protected $fillable = [
        'id', 'roster_member_id', 'cid', 'session_start', 'session_end', 'monitored_position_id', 'duration', 'emails_sent',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->hasOne(MonitoredPosition::class, 'monitored_position_id');
    }

    public function rosterMember()
    {
        return $this->belongsTo(RosterMember::class);
    }

    protected $dates = [
        'session_start', 'session_end',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
