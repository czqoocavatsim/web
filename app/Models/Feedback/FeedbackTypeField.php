<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feedback\FeedbackTypeField
 *
 * @var id         Incremental ID of the feedback type field.
 * @var name       Name of the feedback type field.
 * @var required   Is the field required to submit feedback of this type?
 * @var type_id    ID of feedback type this field belongs to.
 * @var created_at Time created at.
 * @var updated_at Time last updated at.
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property int $required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feedback\FeedbackType $type
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeedbackTypeField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'name', 'required',
    ];

    /**
     * The feedback type this field belongs to.
     *
     * @return \App\Models\Feedback\FeedbackType
     */
    public function type()
    {
        return $this->belongsTo(FeedbackType::class, 'type_id');
    }
}
