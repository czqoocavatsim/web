<?php

namespace App\Models\Training\Instructing\Links;

use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Training\Instructing\Links\InstructorStudentAssignment
 *
 * @property int $id
 * @property int $student_id
 * @property int $instructor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Instructor $instructor
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstructorStudentAssignment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InstructorStudentAssignment extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id',
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
