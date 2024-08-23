<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Records\OTSSession as OtsSession;
use App\Models\Training\Instructing\Records\OTSSessionPassFailRecord;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Notifications\Training\Instructing\NewSessionScheduledStudent;
use App\Notifications\Training\Instructing\SessionAssignedToYou;
use App\Notifications\Training\Instructing\SessionCancelled;
use App\Notifications\Training\Instructing\SessionScheduledTimeChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\DiscordClient;

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

    public function otsSessionsIndex()
    {
        //Get all sessions
        $sessions = OtsSession::all()->sortByDesc('scheduled_time');

        //Return view
        return view('admin.training.instructing.sessions.ots-sessions.index', compact('sessions'));
    }

    public function createTrainingSession(Request $request)
    {
        //Define validator messages
        $messages = [
            'student_id.required'     => 'A student is required.',
            'student_id.integer'      => 'A student is required.',
            'scheduled_time.required' => 'A scheduled time is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'student_id'     => 'required|integer',
            'scheduled_time' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.training-sessions', ['createSessionModal' => 1])->withInput()->withErrors($validator, 'createSessionErrors');
        }

        //Create session
        $session = new TrainingSession([
            'student_id'     => $request->get('student_id'),
            'instructor_id'  => Auth::user()->instructorProfile->id,
            'scheduled_time' => $request->get('scheduled_time'),
        ]);
        $session->save();

        //Notify student
        $session->student->user->notify(new NewSessionScheduledStudent($session, 'training'));

        //Discord notification in instructors channel
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'New training session scheduled #'.$session->id, $session->instructor->user->fullName('FLC').' has scheduled a new training session with '.$session->student->user->fullName('FLC').' on '.$request->get('scheduled_time'));

        //Discord Notification in Training Thread
        $discord = new DiscordClient();
        $discord->sendEmbedInTrainingThread($session->student->user->id, 'Training Session Booked', 
'Hello, '. $session->student->user->fullName('F').'!

A training session has been created for you on <t:'.Carbon::parse($request->get('scheduled_time'))->timestamp.':F>.

Please reach out to your instructor in this training thread if you have any questions.');
        
        //Return
        return redirect()->route('training.admin.instructing.training-sessions.view', $session->id)->with('success', 'Session created!');
    }

    public function viewTrainingSession($session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Get instructors for reassignment modal
        $instructors = Instructor::whereCurrent(true)->get();

        //Get monitored positions for assignment modal
        $positions = MonitoredPosition::all();

        //Return view
        return view('admin.training.instructing.sessions.training-sessions.view', compact('session', 'instructors', 'positions'));
    }

    public function editTrainingSessionTime(Request $request, $session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'new_time.required' => 'A new time is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'new_time' => 'required',
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

         //Discord Notification in Training Thread
         $discord = new DiscordClient();
         $discord->sendEmbedInTrainingThread($session->student->user->id, 'Training Session Time Changed', 
 'Hello, '. $session->student->user->fullName('F').'!
 
 A training session time has been updated. The new time is now <t:'.Carbon::parse($request->get('new_time'))->timestamp.':F>
 
 Please reach out to your instructor in this training thread if you have any questions.');

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
            'instructor_id.integer'  => 'Please select an instructor.',
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

    /**
     * POST request to assign/reassign a position to a training session.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return redirect
     */
    public function assignTrainingSessionPosition(Request $request, $session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'position_id.required' => 'Please select a position.',
            'position_id.integer'  => 'Please select a position.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'position_id' => 'required|integer',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.training-sessions.view', ['id' => $session->id, 'assignPositionModal' => 1])->withInput()->withErrors($validator, 'assignPositionErrors');
        }

        //Find position with that ID
        $position = MonitoredPosition::whereId($request->get('position_id'))->firstOrFail();

        //Assign it to session
        $session->position_id = $position->id;
        $session->save();

        //Redirect
        return redirect()->route('training.admin.instructing.training-sessions.view', $session)->with('success', 'Position assigned!');
    }

    /**
     * GET request to cancel a training session.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return redirect
     */
    public function cancelTrainingSession(Request $request, $session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->firstOrFail();

        //Notify student
        $session->student->user->notify(new SessionCancelled($session, 'training'));

        //Discord Notification in Training Thread
        $discord = new DiscordClient();
        $discord->sendEmbedInTrainingThread($session->student->user->id, 'Training Session Cancelled', 
'Hello, '. $session->student->user->fullName('F').'!

A training session which was planned has been cancelled.

Please contact your instructor if you believe this was a mistake.');

        //Soft delete session
        $session->delete();

        //Return
        return redirect()->route('training.admin.instructing.training-sessions')->with('info', 'Session cancelled.');
    }

    /**
     * AJAX POST request to save training session remarks field.
     *
     * @param Request $request
     *
     * @return response
     */
    public function saveTrainingSessionRemarks(Request $request)
    {
        //Validate
        $validator = Validator::make($request->all(), [
            'session_id' => 'required',
            'remarks'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        //Save to session
        $session = TrainingSession::whereId($request->get('session_id'))->firstOrFail();
        $session->remarks = $request->get('remarks');
        $session->save();

        //Return
        return response()->json(['message' => 'Saved'], 200);
    }

    public function createOtsSession(Request $request)
    {
        //Define validator messages
        $messages = [
            'student_id.required'     => 'A student is required.',
            'student_id.integer'      => 'A student is required.',
            'scheduled_time.required' => 'A scheduled time is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'student_id'     => 'required|integer',
            'scheduled_time' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.training-sessions', ['createSessionModal' => 1])->withInput()->withErrors($validator, 'createSessionErrors');
        }

        //Create session
        $session = new OtsSession([
            'student_id'     => $request->get('student_id'),
            'assessor_id'    => Auth::user()->instructorProfile->id,
            'scheduled_time' => $request->get('scheduled_time'),
        ]);
        $session->save();

        //Notify student
        $session->student->user->notify(new NewSessionScheduledStudent($session, 'ots'));

        //Discord notification in instructors channel
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'New training session scheduled #'.$session->id, $session->instructor->user->fullName('FLC').' has scheduled a new OTS session with '.$session->student->user->fullName('FLC').' on '.$request->get('scheduled_time'));

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session->id)->with('success', 'Session created!');
    }

    public function viewOtsSession($session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Get instructors for reassignment modal
        $instructors = Instructor::whereCurrent(true)->whereAssessor(true)->get();

        //Get monitored positions for assignment modal
        $positions = MonitoredPosition::all();

        //Return view
        return view('admin.training.instructing.sessions.ots-sessions.view', compact('session', 'instructors', 'positions'));
    }

    public function editOtsSessionTime(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'new_time.required' => 'A new time is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'new_time' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.ots-sessions.view', ['id' => $session->id, 'editTimeModal' => 1])->withInput()->withErrors($validator, 'editTimeErrors');
        }

        //Edit time
        $session->scheduled_time = $request->get('new_time');
        $session->save();

        //Notify
        $session->student->user->notify(new SessionScheduledTimeChanged($session, 'ots'));

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session)->with('success', 'Scheduled time changed!');
    }

    public function reassignOtsSessionInstructor(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'instructor_id.required' => 'Please select an instructor.',
            'instructor_id.integer'  => 'Please select an instructor.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|integer',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.ots-sessions.view', ['id' => $session->id, 'reassignInstructorModal' => 1])->withInput()->withErrors($validator, 'reassignInstructorErrors');
        }

        //Change instructor over
        $instructor = Instructor::whereId($request->get('instructor_id'))->first();
        $session->assessor_id = $instructor->id;
        $session->save();

        //Notify instructor
        $instructor->notify(new SessionAssignedToYou($session, 'ots'));

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session)->with('success', 'Assigned instructor changed!');
    }

    /**
     * POST request to assign/reassign a position to a training session.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return redirect
     */
    public function assignOtsSessionPosition(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'position_id.required' => 'Please select a position.',
            'position_id.integer'  => 'Please select a position.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'position_id' => 'required|integer',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.ots-sessions.view', ['id' => $session->id, 'assignPositionModal' => 1])->withInput()->withErrors($validator, 'assignPositionErrors');
        }

        //Find position with that ID
        $position = MonitoredPosition::whereId($request->get('position_id'))->firstOrFail();

        //Assign it to session
        $session->position_id = $position->id;
        $session->save();

        //Redirect
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session)->with('success', 'Position assigned!');
    }

    /**
     * GET request to cancel a training session.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return redirect
     */
    public function cancelOtsSession(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Notify student
        $session->student->user->notify(new SessionCancelled($session, 'ots'));

        //Soft delete session
        $session->delete();

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions')->with('info', 'Session cancelled.');
    }

    /**
     * AJAX POST request to save training session remarks field.
     *
     * @param Request $request
     *
     * @return response
     */
    public function saveOtsSessionRemarks(Request $request)
    {
        //Validate
        $validator = Validator::make($request->all(), [
            'session_id' => 'required',
            'remarks'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        //Save to session
        $session = OtsSession::whereId($request->get('session_id'))->firstOrFail();
        $session->remarks = $request->get('remarks');
        $session->save();

        //Return
        return response()->json(['message' => 'Saved'], 200);
    }

    /**
     * POST request to mark an OTS session as passed.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return void
     */
    public function markOtsSessionAsPassed(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'reportFile.mimes' => 'Please upload a PDF or DOCX/DOC/RTF document for the report.',
            'reportFile.files' => 'Please upload a PDF or DOCX/DOC/RTF document for the report.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'reportFile' => 'mimes:pdf,docx,doc,rtf|file',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.ots-sessions.view', ['id' => $session->id, 'passModal' => 1])->withInput()->withErrors($validator, 'markPassedErrors');
        }

        //Put the report onto disk
        $reportPath = null;
        if ($request->file('reportFile')) {
            $reportPath = Storage::disk('digitalocean')->put('staff_uploads/training/otsreports/'.uniqid(), $request->file('reportFile'), 'public');
        }

        //Create record
        $record = new OTSSessionPassFailRecord([
            'ots_session_id' => $session->id,
            'result'         => 'passed',
            'assessor_id'    => Auth::user()->instructorProfile->id,
            'report_url'     => $reportPath ? Storage::url($reportPath) : null,
            'remarks'        => $request->get('remarks'),
        ]);
        $record->save();

        //Adjust session
        $session->result = 'passed';
        $session->save();

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session)->with('success', 'Session marked as Passed! Be sure to inform the student of their result and update the roster and their student status accordingly.');
    }

    /**
     * POST request to mark an OTS session as failed.
     *
     * @param Request $request
     * @param int     $session_id
     *
     * @return void
     */
    public function markOtsSessionAsFailed(Request $request, $session_id)
    {
        //Get session
        $session = OtsSession::whereId($session_id)->firstOrFail();

        //Define validator messages
        $messages = [
            'reportFile.mimes' => 'Please upload a PDF or DOCX/DOC/RTF document for the report.',
            'reportFile.files' => 'Please upload a PDF or DOCX/DOC/RTF document for the report.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'reportFile' => 'mimes:pdf,docx,doc,rtf|file',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.ots-sessions.view', ['id' => $session->id, 'passModal' => 1])->withInput()->withErrors($validator, 'markPassedErrors');
        }

        //Put the report onto disk
        $reportPath = null;
        if ($request->file('reportFile')) {
            $reportPath = Storage::disk('digitalocean')->put('staff_uploads/training/otsreports/'.uniqid(), $request->file('reportFile'), 'public');
        }

        //Create record
        $record = new OTSSessionPassFailRecord([
            'ots_session_id' => $session->id,
            'result'         => 'failed',
            'assessor_id'    => Auth::user()->instructorProfile->id,
            'report_url'     => $reportPath ? Storage::url($reportPath) : null,
            'remarks'        => $request->get('remarks'),
        ]);
        $record->save();

        //Adjust session
        $session->result = 'failed';
        $session->save();

        //Return
        return redirect()->route('training.admin.instructing.ots-sessions.view', $session)->with('info', 'Session marked as failed.');
    }
}
