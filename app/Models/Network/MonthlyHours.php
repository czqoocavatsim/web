<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// A position monitored by the activity bot
/**
 * App\Models\Network\MonthlyHours
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyHours newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyHours newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonthlyHours query()
 * @mixin \Eloquent
 */
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
