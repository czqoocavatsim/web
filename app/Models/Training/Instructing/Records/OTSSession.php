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
 * App\Models\Training\Instructing\Records\OTSSession
 *
 * @property int $id
 * @property int $student_id
 * @property int $assessor_id
 * @property \Illuminate\Support\Carbon $scheduled_time
 * @property string|null $remarks
 * @property string $result
 * @property int|null $position_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $reminder_sent
 * @property-read Instructor $instructor
 * @property-read \App\Models\Training\Instructing\Records\OTSSessionPassFailRecord|null $passFailRecord
 * @property-read MonitoredPosition|null $position
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession newQuery()
 * @method static \Illuminate\Database\Query\Builder|OTSSession onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereAssessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereReminderSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OTSSession withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OTSSession withoutTrashed()
 * @mixin \Eloquent
 */
class OTSSession extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'assessor_id', 'scheduled_time', 'remarks', 'result', 'position_id',
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
        return $this->belongsTo(Instructor::class, 'assessor_id');
    }

    public function position()
    {
        return $this->belongsTo(MonitoredPosition::class, 'position_id');
    }

    public function passFailRecord()
    {
        return $this->hasOne(OTSSessionPassFailRecord::class, 'ots_session_id');
    }

    public function remarksHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->remarks));
    }
}
