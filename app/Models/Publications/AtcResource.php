<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Publications\AtcResource
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $atc_only
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereAtcOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AtcResource whereUserId($value)
 * @mixin \Eloquent
 */
class AtcResource extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'title', 'description', 'url', 'atc_only',
    ];

    protected $hidden = ['id'];

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
