<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Records\TrainingSession;
use Illuminate\Http\Request;

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

        //Return view
        return view('admin.training.instructing.sessions.training-sessions.view', compact('session'));
    }

    public function editTrainingSessionTime(Request $request, $session_id)
    {

    }
}
