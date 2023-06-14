<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Publications\UploadedImage
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage newQuery()
 * @method static \Illuminate\Database\Query\Builder|UploadedImage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedImage whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|UploadedImage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UploadedImage withoutTrashed()
 * @mixin \Eloquent
 */
class UploadedImage extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'user_id', 'path',
    ];

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
