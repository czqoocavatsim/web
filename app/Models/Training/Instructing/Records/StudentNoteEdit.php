<?php

namespace App\Models\Training\Instructing\Records;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Training\Instructing\Records\StudentNoteEdit
 *
 * @property int $id
 * @property int $instructor_id
 * @property int $student_note_id
 * @property string $content_as_of_edit
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereContentAsOfEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereStudentNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentNoteEdit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentNoteEdit extends Model
{
    //
}
