<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function studentTrainingNotes($student_id)
    {
        //Get student
        $student = Student::where('user_id', $student_id)->firstOrFail();

        //Get their training notes
        $notes = $student->notes;

        //Return view
        return view('admin.training.instructing.students.training-notes', compact('student', 'notes'));
    }
}
