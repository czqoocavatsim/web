<?php

namespace App\Models\Settings;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Settings\CoreSettings
 *
 * @property int $id
 * @property string $sys_name
 * @property string $release
 * @property string $sys_build
 * @property string $copyright_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $banner
 * @property string $bannerMode
 * @property string $bannerLink
 * @property string $emailfirchief
 * @property string $emaildepfirchief
 * @property string $emailcinstructor
 * @property string $emaileventc
 * @property string $emailfacilitye
 * @property string $emailwebmaster
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereBannerLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereBannerMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereCopyrightYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmailcinstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmaildepfirchief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmaileventc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmailfacilitye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmailfirchief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereEmailwebmaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereRelease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereSysBuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereSysName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreSettings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoreSettings extends Model
{
    use LogsActivity;

    protected $table = 'core_info';

    protected $fillable = [
        'sys_name', 'release', 'sys_build', 'copyright_year', 'banner',
        'emailfirchief', 'emaildepfirchief', 'emailcinstructor',
        'emaileventc', 'emailfacilitye', 'emailwebmaster',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
