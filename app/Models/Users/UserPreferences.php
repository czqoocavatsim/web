<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserPreferences
 *
 * @property int $id
 * @property int $user_id
 * @property int $enable_beta_components
 * @property string $ui_mode
 * @property int $enable_discord_notifications
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $accent_colour
 * @property-read \App\Models\Users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereAccentColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereEnableBetaComponents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereEnableDiscordNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereUiMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPreferences whereUserId($value)
 * @mixin \Eloquent
 */
class UserPreferences extends Model
{
    protected $hidden = ['id'];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
