<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feedback\FeedbackTypeFieldSubmission
 *
 * @var id            Incremental ID of the feedback type field submission.
 * @var name          Name of the feedback type field.
 * @var type_id       ID of feedback type this field belongs to.
 * @var submission_id ID of feedback submission this belongs to.
 * @var content       Content of submission for field.
 * @var created_at    Time created at.
 * @var updated_at    Time last updated at.
 * @property int $id
 * @property int $type_id
 * @property int $submission_id
 * @property string $name
 * @property string $content
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feedback\FeedbackSubmission $submission
 * @property-read \App\Models\Feedback\FeedbackType $type
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTypeFieldSubmission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeedbackTypeFieldSubmission extends Model
{
    /**
     * The attributes that are mass assignble.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'submission_id', 'name', 'content',
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
