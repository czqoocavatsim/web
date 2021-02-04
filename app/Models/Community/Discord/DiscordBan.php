<?php

namespace App\Models\Community\Discord;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Community\Discord\DiscordBan
 *
 * @property int $id
 * @property int $user_id
 * @property int $moderator_id
 * @property string|null $deleted_at
 * @property string $reason
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property int|null $discord_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $moderator
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereDiscordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereModeratorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscordBan whereUserId($value)
 * @mixin \Eloquent
 */
class DiscordBan extends Model
{
    protected $hidden = ['id'];
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i';
    }

    protected $fillable = [
        'user_id', 'moderator_id', 'reason', 'start_time', 'end_time', 'discord_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function reasonHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->reason));
    }

    protected $dates = [
        'start_time', 'end_time',
    ];
}
