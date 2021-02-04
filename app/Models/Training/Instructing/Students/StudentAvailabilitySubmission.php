<?php

namespace App\Models\Training\Instructing\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\Instructing\Students\StudentAvailabilitySubmission
 *
 * @property int $id
 * @property int $student_id
 * @property string $submission
 * @property string|null $extra_information
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Training\Instructing\Students\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereExtraInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereSubmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAvailabilitySubmission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentAvailabilitySubmission extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'submission', 'extra_comments',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function submissionHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->submission));
    }

    public function extraCommentsHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->extra_comments));
    }
}
