<?php

namespace App\Models\Settings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Settings\RotationImage
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RotationImage whereUserId($value)
 * @mixin \Eloquent
 */
class RotationImage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
