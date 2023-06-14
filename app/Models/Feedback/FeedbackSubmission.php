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

/**
 * App\Models\Feedback\FeedbackSubmission
 *
 * @var id                    Incremental ID of feedback.
 * @var user_id               User foreign key for whoever submitted the feedback.
 * @var type_id               Feedback type foreign key for the type of feedback it is.
 * @var submission_content    Content of the submission in Markdown format.
 * @var permission_to_publish Has the user given permission for this feedback to be published?
 * @var slug                  URL slug of type.
 * @var created_at            Time feedback submitted at.
 * @var updated_at            Time feedback last updated at.
 * @property int $id
 * @property string $slug
 * @property int $user_id
 * @property int $type_id
 * @property string $submission_content
 * @property int $permission_to_publish
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback\FeedbackTypeFieldSubmission[] $fields
 * @property-read int|null $fields_count
 * @property-read \App\Models\Feedback\FeedbackType $type
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission newQuery()
 * @method static \Illuminate\Database\Query\Builder|FeedbackSubmission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission wherePermissionToPublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereSubmissionContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackSubmission whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|FeedbackSubmission withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FeedbackSubmission withoutTrashed()
 * @mixin \Eloquent
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
