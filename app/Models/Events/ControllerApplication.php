<?php

namespace App\Models\Events;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * App\Models\Events\ControllerApplication
 *
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string $start_availability_timestamp
 * @property string $end_availability_timestamp
 * @property string|null $comments
 * @property string $submission_timestamp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Events\Event $event
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereEndAvailabilityTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereStartAvailabilityTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereSubmissionTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerApplication whereUserId($value)
 * @mixin \Eloquent
 */
class ControllerApplication extends Model
{
    use LogsActivity;

    protected $table = 'event_controller_applications';

    protected $fillable = [
        'id', 'event_id', 'user_id', 'start_availability_timestamp', 'end_availability_timestamp', 'comments', 'submission_timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
