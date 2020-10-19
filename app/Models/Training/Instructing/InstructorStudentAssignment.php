<?php

namespace App\Models\Training\Instructing;

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
