<?php

namespace App\Models\News;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\News\HomeNewControllerCert
 *
 * @property int $id
 * @property int $controller_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $timestamp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $controller
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomeNewControllerCert whereUserId($value)
 * @mixin \Eloquent
 */
class HomeNewControllerCert extends Model
{
    public function controller()
    {
        return $this->belongsTo(User::class, 'controller_id');
    }

    protected $dates = ['timestamp'];
}
