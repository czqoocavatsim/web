<?php

namespace App\Models\Feedback;

use Parsedown;
use App\Models\Users\User;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use App\Models\Feedback\FeedbackType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Feedback\FeedbackTypeFieldSubmission;

class FeedbackComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feedback_submission_id', 'comment_type', 'submission_content', 'user_id', 'id',
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

    public function feedbackCommentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->submission_content));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }

    /**
     * Returns the user who submitted the feedback.
     *
     * @return \App\Models\Users\User
     */
    
}
