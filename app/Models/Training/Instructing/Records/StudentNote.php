<?php

namespace App\Models\Training\Instructing\Records;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentNote extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id', 'content', 'staff_only'
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
