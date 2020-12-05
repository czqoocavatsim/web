<?php

namespace App\Models\Training\Instructing\Links;

use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;

class InstructorStudentAssignment extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
