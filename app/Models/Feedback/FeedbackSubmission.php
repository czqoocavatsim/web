<?php

namespace App\Models\Feedback;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @var id                    Incremental ID of feedback.
 * @var user_id               User foreign key for whoever submitted the feedback.
 * @var type_id               Feedback type foreign key for the type of feedback it is.
 * @var submission_content    Content of the submission in Markdown format.
 * @var permission_to_publish Has the user given permission for this feedback to be published?
 * @var slug                  URL slug of type.
 * @var created_at            Time feedback submitted at.
 * @var updated_at            Time feedback last updated at.
 */
class FeedbackSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type_id', 'submission_content', 'permission_to_publish', 'slug',
    ];

    /**
     * The attributes that are hidden from arrays.
     *
     * @var array
     */
    protected $hidden = ['id'];

    //Soft deletes and logging
    use SoftDeletes;
    use LogsActivity;

    /**
     * Returns the user who submitted the feedback.
     *
     * @return \App\Models\Users\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the model of the type of feedback it is.
     *
     * @return \App\Models\Feedback\FeedbackType
     */
    public function type()
    {
        return $this->belongsTo(FeedbackType::class, 'type_id');
    }

    /**
     * Returns HTML string for submission content.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function submissionContentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->submission_content));
    }

    /**
     * Return the fields of the submission.
     *
     * @return \App\Models\Feedback\FeedbackTypeFieldSubmission
     */
    public function fields()
    {
        return $this->hasMany(FeedbackTypeFieldSubmission::class, 'submission_id');
    }
}
