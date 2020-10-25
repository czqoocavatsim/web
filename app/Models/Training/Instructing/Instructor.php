<?php

namespace App\Models\Training\Instructing;

use App\Models\Users\User;
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

    public function otsSessions()
    {
        return $this->hasMany(OTSSession::class, 'instructor_id');
    }

    public function studentsAssigned()
    {
        return $this->hasMany(InstructorStudentAssignment::class, 'instructor_id');
    }
}
