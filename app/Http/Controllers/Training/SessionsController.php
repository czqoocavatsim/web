<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Notifications\Training\Instructing\SessionAssignedToYou;
use App\Notifications\Training\Instructing\SessionScheduledTimeChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionsController extends Controller
{
    public function yourUpcomingSessions()
    {
        return view('admin.training.instructing.sessions.your-upcoming-sessions');
    }

    public function trainingSessionsIndex()
    {
        //Get all sessions
        $sessions = TrainingSession::all()->sortByDesc('scheduled_time');

        //Return view
        return view('admin.training.instructing.sessions.training-sessions.index', compact('sessions'));
    }

    public function viewTrainingSession($session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Get instructors for reassignment modal
        $instructors = Instructor::whereCurrent(true)->get();

        //Return view
        return view('admin.training.instructing.sessions.training-sessions.view', compact('session', 'instructors'));
    }

    public function editTrainingSessionTime(Request $request, $session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'new_time.required' => 'A new time is required.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'new_time' => 'required'
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.training-sessions.view', ['id' => $session->id, 'editTimeModal' => 1])->withInput()->withErrors($validator, 'editTimeErrors');
        }

        //Edit time
        $session->scheduled_time = $request->get('new_time');
        $session->save();

        //Notify
        $session->student->user->notify(new SessionScheduledTimeChanged($session, 'training'));

        //Return
        return redirect()->route('training.admin.instructing.training-sessions.view', $session)->with('success', 'Scheduled time changed!');
    }

    public function reassignTrainingSessionInstructor(Request $request, $session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'instructor_id.required' => 'Please select an instructor.',
            'instructor_id.integer' => 'Please select an instructor.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|integer',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.training-sessions.view', ['id' => $session->id, 'reassignInstructorModal' => 1])->withInput()->withErrors($validator, 'reassignInstructorErrors');
        }

        //Change instructor over
        $instructor = Instructor::whereId($request->get('instructor_id'))->first();
        $session->instructor_id = $instructor->id;
        $session->save();

        //Notify instructor
        $instructor->notify(new SessionAssignedToYou($session, 'training'));

        //Return
        return redirect()->route('training.admin.instructing.training-sessions.view', $session)->with('success', 'Assigned instructor changed!');
    }
}
