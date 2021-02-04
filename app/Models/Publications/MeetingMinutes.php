<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Publications\MeetingMinutes
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes newQuery()
 * @method static \Illuminate\Database\Query\Builder|MeetingMinutes onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingMinutes whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|MeetingMinutes withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MeetingMinutes withoutTrashed()
 * @mixin \Eloquent
 */
class MeetingMinutes extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function descriptionHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }
}
