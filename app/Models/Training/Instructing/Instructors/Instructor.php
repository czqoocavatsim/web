<?php

namespace App\Models\Training\Instructing\Instructors;

use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Training\Instructing\Instructors\Instructor
 *
 * @property int $id
 * @property int $user_id
 * @property int $current
 * @property int $assessor
 * @property string|null $staff_email
 * @property string|null $staff_page_tagline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|OTSSession[] $otsSessions
 * @property-read int|null $ots_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|InstructorStudentAssignment[] $studentsAssigned
 * @property-read int|null $students_assigned_count
 * @property-read \Illuminate\Database\Eloquent\Collection|TrainingSession[] $trainingSessions
 * @property-read int|null $training_sessions_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereAssessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereStaffEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereStaffPageTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereUserId($value)
 * @mixin \Eloquent
 */
class Instructor extends Model
{
    protected $hidden = ['id'];

    use Notifiable;

    protected $fillable = [
        'user_id', 'current', 'assessor', 'staff_email', 'staff_page_tagline',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function email()
    {
        if ($this->staff_email) {
            return $this->staff_email;
        } else {
            return $this->user->email;
        }
    }

    public function staffPageTagline()
    {
        if ($this->staff_page_tagline) {
            return $this->staff_page_tagline;
        } else {
            if ($this->assessor) {
                return 'Assessor';
            } else {
                return 'Instructor';
            }
        }
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class, 'instructor_id');
    }

    public function upcomingTrainingSessions()
    {
        return TrainingSession::where('instructor_id', $this->id)->where('scheduled_time', '>', Carbon::now())->get();
    }

    public function upcomingOtsSessions()
    {
        return OTSSession::where('assessor_id', $this->id)->where('scheduled_time', '>', Carbon::now())->get();
    }

    public function otsSessions()
    {
        return $this->hasMany(OTSSession::class, 'instructor_id');
    }

    public function studentsAssigned()
    {
        return $this->hasMany(InstructorStudentAssignment::class, 'instructor_id');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email();
    }
}
