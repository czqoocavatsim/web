<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

/**
 * @var id Incremental ID of the feedback type field submission.
 * @var name Name of the feedback type field.
 * @var type_id ID of feedback type this field belongs to.
 * @var submission_id ID of feedback submission this belongs to.
 * @var content Content of submission for field.
 * @var created_at Time created at.
 * @var updated_at Time last updated at.
 */
class FeedbackTypeFieldSubmission extends Model
{
    /**
     * The attributes that are mass assignble.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'submission_id', 'name', 'content'
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

    /**
     * The feedback submission this field belongs to.
     *
     * @return \App\Models\Feedback\FeedbackSubmission
     */
    public function submission()
    {
        return $this->belongsTo(FeedbackSubmission::class, 'submission_id');
    }
}
