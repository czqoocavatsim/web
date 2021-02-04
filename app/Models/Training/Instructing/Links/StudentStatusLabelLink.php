<?php

namespace App\Models\Training\Instructing\Links;

use App\Models\Training\Instructing\Students\Student;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Training\Instructing\Links\StudentStatusLabelLink
 *
 * @property int $id
 * @property int $student_status_label_id
 * @property int $student_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink whereStudentStatusLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabelLink whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentStatusLabelLink extends Model
{
    protected $fillable = [
        'student_status_label_id', 'student_id',
    ];

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
