<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Training\ApplicationReferee
 *
 * @property int $id
 * @property int $application_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $referee_full_name
 * @property string|null $referee_email
 * @property string|null $referee_staff_position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Training\Application $application
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationReferee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereRefereeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereRefereeFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereRefereeStaffPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationReferee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationReferee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationReferee withoutTrashed()
 * @mixin \Eloquent
 */
class ApplicationReferee extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'application_id', 'referee_full_name', 'referee_email', 'referee_staff_position',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
