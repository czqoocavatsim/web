<?php

namespace App\Models\Training\Instructing\Links;

use App\Models\Training\Instructing\Students\Student;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use Illuminate\Database\Eloquent\Model;

class StudentStatusLabelLink extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function label()
    {
        //return $this->belongsTo(StudentStatusLabel::class, 'student_status_label_id');
        return StudentStatusLabel::whereId($this->student_status_label_id)->first();
    }
}
