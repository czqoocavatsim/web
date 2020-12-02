<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Records\StudentNote;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class RecordsController extends Controller
{
    public function studentTrainingNotes($student_id)
    {
        //Get student
        $student = Student::where('user_id', $student_id)->firstOrFail();

        //Get their training notes
        $notes = $student->notes->sortByDesc('created_at');

        //Get their recommendations
        $recommendations = $student->recommendations->sortByDesc('created_at');

        //Return view
        return view('admin.training.instructing.students.training-notes.index', compact('student', 'notes', 'recommendations'));
    }

    public function createStudentTrainingNote($student_id)
    {
        //Get student
        $student = Student::where('user_id', $student_id)->firstOrFail();

        //Return view
        return view('admin.training.instructing.students.training-notes.create', compact('student'));
    }

    public function createStudentTrainingNotePost(Request $request, $student_id)
    {
        //Get student
        $student = Student::where('user_id', $student_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'content.required' => 'Content is required',
            'visibility.required' => 'A visibility setting is required',
            'visibility.integer' => 'A visibilty setting is required'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'visibility' => 'required|integer'
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.students.records.training-notes.create', $student->user_id)->withInput()->withErrors($validator, 'createTrainingNoteErrors');
        }

        //Create note
        $note = new StudentNote([
            'student_id' => $student->id,
            'instructor_id' => Auth::user()->instructorProfile->id,
            'content' => $request->get('content'),
            'staff_only' => $request->get('visibility')
        ]);
        $note->save();

        //Return to notes
        return redirect()->route('training.admin.instructing.students.records.training-notes', $student->user_id)->with('success', 'Note added!');
    }

    public function deleteStudentTrainingNote($student_id, $training_note_id)
    {
        //Get student
        $student = Student::where('user_id', $student_id)->firstOrFail();

        //Get note
        $note = StudentNote::whereId($training_note_id)->where('student_id', $student->id)->firstOrFail();

        //Delete note
        $note->delete();

        //Return to notes
        return redirect()->route('training.admin.instructing.students.records.training-notes', $student->user_id)->with('info', 'Note deleted.');
    }
}
