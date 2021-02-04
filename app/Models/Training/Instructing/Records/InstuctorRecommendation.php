<?php

namespace App\Models\Training\Instructing\Records;

use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Training\Instructing\Records\InstuctorRecommendation
 *
 * @property int $id
 * @property int $student_id
 * @property int $instructor_id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Instructor $instructor
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstuctorRecommendation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InstuctorRecommendation extends Model
{
    protected $fillable = [
        'student_id', 'instructor_id', 'type',
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
