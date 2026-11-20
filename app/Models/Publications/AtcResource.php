<?php

namespace App\Models\Publications;

use Parsedown;
use App\Models\Users\User;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
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
        'user_id', 'title', 'description', 'url', 'atc_only', 'sort_order',
    ];

    protected $hidden = ['id'];

    /** All resources in display order (lowest sort_order first, oldest first on a tie). */
    public static function ordered()
    {
        return static::orderBy('sort_order')->orderBy('id')->get();
    }

    /** Sort order for a new resource: after everything that exists. */
    public static function nextSortOrder(): int
    {
        return ((int) static::max('sort_order')) + 10;
    }

    /**
     * Move one place up or down. The list is renumbered 10, 20, 30... first so
     * ties can never stop a move, then the two neighbours swap places.
     */
    public static function move(int $id, string $direction): void
    {
        $list = static::ordered()->values();
        foreach ($list as $i => $r) {
            $r->sort_order = ($i + 1) * 10;
        }
        $at = $list->search(fn ($r) => $r->id === $id);
        if ($at !== false) {
            $to = $direction === 'up' ? $at - 1 : $at + 1;
            if (isset($list[$to])) {
                [$list[$at]->sort_order, $list[$to]->sort_order] = [$list[$to]->sort_order, $list[$at]->sort_order];
            }
        }
        foreach ($list as $r) {
            if ($r->isDirty('sort_order')) {
                $r->save();
            }
        }
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
