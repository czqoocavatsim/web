<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * App\Models\Feedback\FeedbackType
 *
 * @var id                 Incremental ID of the feedback type.
 * @var name               Name of the feedback type.
 * @var description        Description of the feedback type.
 * @var visible_to_role_id ID of role that can view submissions of this type.
 * @var slug               URL slug of type.
 * @var created_at         Time created at.
 * @var updated_at         Time last updated at.
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property int $visible_to_role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback\FeedbackTypeField[] $fields
 * @property-read int|null $fields_count
 * @property-read Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback\FeedbackSubmission[] $submissions
 * @property-read int|null $submissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackType whereVisibleToRoleId($value)
 * @mixin \Eloquent
 */
class FeedbackType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'visible_to_role_id', 'slug',
    ];

    /**
     * Returns the role that can view feedback of this type.
     *
     * @return Spatie\Permission\Models\Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'visible_to_role_id');
    }

    /**
     * Return the feedback submissions belonging to this type.
     *
     * @return \App\Models\Feedback\FeedbackSubmission
     */
    public function submissions()
    {
        return $this->hasMany(FeedbackSubmission::class, 'type_id');
    }

    /**
     * Return the fields belonging to this type.
     *
     * @return \App\Models\Feedback\FeedbackTypeField
     */
    public function fields()
    {
        return $this->hasMany(FeedbackTypeField::class, 'type_id');
    }
}
