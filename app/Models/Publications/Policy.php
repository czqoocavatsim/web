<?php

namespace App\Models\Publications;

use Parsedown;
use App\Models\Users\User;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Publications\Policy
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
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newQuery()
 * @method static \Illuminate\Database\Query\Builder|Policy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Policy withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Policy withoutTrashed()
 * @mixin \Eloquent
 */
class Policy extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'url',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function descriptionHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
