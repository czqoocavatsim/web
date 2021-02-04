<?php

namespace App\Models\Training\Instructing\Records;

use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\Instructing\Records\StudentNote
 *
 * @property int $id
 * @property int $student_id
 * @property int $instructor_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $staff_only
 * @property-read Instructor $instructor
 * @property-read Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote newQuery()
 * @method static \Illuminate\Database\Query\Builder|StudentNote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereStaffOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|StudentNote withTrashed()
 * @method static \Illuminate\Database\Query\Builder|StudentNote withoutTrashed()
 * @mixin \Eloquent
 */
class StudentNote extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id', 'content', 'staff_only',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function contentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }
}
