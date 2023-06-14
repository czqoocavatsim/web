<?php

namespace App\Models\News;

use Parsedown;
use App\Models\Users\User;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\News\Announcement
 *
 * @property int $id
 * @property int $user_id
 * @property string $target_group
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property string|null $reason_for_sending
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereReasonForSending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTargetGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUserId($value)
 * @mixin \Eloquent
 */
class Announcement extends Model
{
    use LogsActivity;

    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'target_group', 'title', 'content', 'slug', 'reason_for_sending', 'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
