<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserNotification
 *
 * @property int $id
 * @property int $user_id
 * @property string $notification_id
 * @property string $dateTime
 * @property string $content
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotification whereUserId($value)
 * @mixin \Eloquent
 */
class UserNotification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id', 'content', 'link', 'dateTime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function send(User $user, $content, $link)
    {
        $notification = new self();
        $notification->content = $content;
        $notification->user_id = $user->id;
        $notification->link = $link;
        $notification->dateTime = date('Y-m-d H:i:s');
        $notification->save();
    }
}
