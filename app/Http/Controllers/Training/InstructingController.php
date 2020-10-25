<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Instructor;
use App\Models\Training\Instructing\OTSSession;
use App\Models\Training\Instructing\TrainingSession;
use Illuminate\Http\Request;

class InstructingController extends Controller
{
    public function calendar()
    {
        //Get all sessions
        $trainingSessions = TrainingSession::with('student.user')->with('instructor.user')->with('position')->get()->sortBy('scheduled_time');
        $otsSessions = OTSSession::with('student.user')->with('instructor.user')->with('position')->get()->sortBy('scheduled_time');

        return view('admin.training.instructing.calendar', compact('trainingSessions', 'otsSessions'));
    }

    public function instructors()
    {
        //Get all instructors
        $instructors = Instructor::where('current', true)->get();

        //Return view
        return view('admin.training.instructing.instructors.index', compact('instructors'));
    }

    public function viewInstructor($cid)
    {
        //Get the instructor
        $instructor = Instructor::where('user_id', $cid)->firstOrFail();

        //Return view
        return view('admin.training.instructing.instructors.instructor', compact('instructor'));
    }
}
