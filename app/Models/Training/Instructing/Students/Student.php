<?php

namespace App\Models\Training\Instructing\Students;

use App\Models\Training\Application;
use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'current'
    ];

    public function instructor()
    {
        //Find instructor this user is assigned to
        return InstructorStudentAssignment::where('student_id', $this->id)->first();
    }

    public function labels()
    {
        return $this->hasMany(StudentStatusLabelLink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(StudentNote::class, 'student_id');
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class, 'student_id');
    }

    public function otsSessions()
    {
        return $this->hasMany(OTSSession::class, 'student_id');
    }

    public function application()
    {
        //Find latest accepted application from user
        return Application::where('user_id', $this->user_id)->where('status', 1)->latest('created_at')->first();
    }
}
