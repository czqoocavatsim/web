<?php

namespace App\Models\News;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\News\News
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $show_author
 * @property string|null $image
 * @property string|null $content
 * @property string|null $summary
 * @property \Illuminate\Support\Carbon $published
 * @property \Illuminate\Support\Carbon|null $edited
 * @property int $visible
 * @property int $email_level
 * @property int $certification
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCertification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereEdited($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereEmailLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereShowAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereVisible($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    use LogsActivity;

    protected $fillable = [
        'id', 'title', 'user_id', 'show_author', 'image', 'content', 'summary', 'published', 'edited', 'visible', 'email_level', 'certification', 'slug',
    ];

    /*
     * * Return who posted the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function published_pretty()
    {
        $t = $this->published;

        return $t->day.' '.$t->monthName.' '.$t->year;
    }

    public function edited_pretty()
    {
        if (!$this->edited) {
            return null;
        }

        return $this->edited->toDayDateTimeString();
    }

    public function author_pretty()
    {
        if (!$this->show_author) {
            return 'Gander Oceanic Staff';
        }

        return $this->user->fullName('FLC');
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }

    protected $dates = [
        'published', 'edited',
    ];
}
