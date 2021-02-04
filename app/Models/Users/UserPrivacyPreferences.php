<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserPrivacyPreferences
 *
 * @property int $id
 * @property int $user_id
 * @property int $avatar_public
 * @property int $biography_public
 * @property int $session_logs_public
 * @property int $certification_details_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereAvatarPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereBiographyPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereCertificationDetailsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereSessionLogsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPrivacyPreferences whereUserId($value)
 * @mixin \Eloquent
 */
class UserPrivacyPreferences extends Model
{
    //
}
