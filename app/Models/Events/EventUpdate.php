<?php

namespace App\Models\Events;

use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Events\EventUpdate
 *
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string|null $title
 * @property string $content
 * @property string $created_timestamp
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Events\Event $event
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereCreatedTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventUpdate whereUserId($value)
 * @mixin \Eloquent
 */
class EventUpdate extends Model
{
    use LogsActivity;

    protected $fillable = [
        'event_id', 'user_id', 'title', 'content', 'created_timestamp', 'slug',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function created_pretty()
    {
        $t = Carbon::create($this->created_timestamp);

        return $t->day.' '.$t->monthName.' '.$t->year.' '.$t->format('H:i').' Zulu';
    }

    public function author_pretty()
    {
        return $this->user->fullName('FLC');
    }
}
