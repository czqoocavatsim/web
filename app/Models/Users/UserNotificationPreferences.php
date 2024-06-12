<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserNotificationPreferences
 *
 * @property int $id
 * @property int $user_id
 * @property string $training_notifications
 * @property string $event_notifications
 * @property string $news_notifications
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereEventNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereNewsNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereTrainingNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotificationPreferences whereUserId($value)
 * @mixin \Eloquent
 */
class UserNotificationPreferences extends Model
{
    protected $guarded = [];
}
