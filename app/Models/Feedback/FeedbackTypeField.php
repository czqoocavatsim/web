<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

/**
 * @var id         Incremental ID of the feedback type field.
 * @var name       Name of the feedback type field.
 * @var required   Is the field required to submit feedback of this type?
 * @var type_id    ID of feedback type this field belongs to.
 * @var created_at Time created at.
 * @var updated_at Time last updated at.
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
