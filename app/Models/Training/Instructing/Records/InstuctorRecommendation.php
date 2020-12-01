<?php

namespace App\Models\Training\Instructing\Records;

use Illuminate\Database\Eloquent\Model;

class InstuctorRecommendation extends Model
{
    protected $fillable = [
        'student_id', 'instructor_id', 'type'
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
