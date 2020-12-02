<?php

namespace App\Models\Training\Instructing\Instructors;

use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'current', 'assessor', 'staff_email', 'staff_page_tagline'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function email()
    {
        if ($this->staff_email)
        {
            return $this->staff_email;
        }
        else
        {
            return $this->user->email;
        }
    }

    public function staffPageTagline()
    {
        if ($this->staff_page_tagline)
        {
            return $this->staff_page_tagline;
        }
        else
        {
            if ($this->assessor)
            {
                return "Assessor";
            }
            else
            {
                return "Instructor";
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
}
