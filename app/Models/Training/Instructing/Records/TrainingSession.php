<?php

namespace App\Models\Training\Instructing\Records;

use App\Models\Network\MonitoredPosition;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\Instructing\Records\TrainingSession
 *
 * @property int $id
 * @property int $student_id
 * @property int $instructor_id
 * @property \Illuminate\Support\Carbon $scheduled_time
 * @property string|null $remarks
 * @property int|null $position_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $reminder_sent
 * @property-read Instructor $instructor
 * @property-read MonitoredPosition|null $position
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession newQuery()
 * @method static \Illuminate\Database\Query\Builder|TrainingSession onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereReminderSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|TrainingSession withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TrainingSession withoutTrashed()
 * @mixin \Eloquent
 */
class TrainingSession extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id', 'scheduled_time', 'remarks', 'position_id', 'reminder_sent',
    ];

    protected $dates = [
        'scheduled_time',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function position()
    {
        return $this->belongsTo(MonitoredPosition::class, 'position_id');
    }

    public function remarksHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->remarks));
    }
}
