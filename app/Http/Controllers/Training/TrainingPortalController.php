<?php

namespace App\Http\Controllers\Training;

use App\Models\Training\ControllerAcknowledgement;
use Illuminate\Http\Request;
use App\Services\DiscordClient;
use App\Models\News\Announcement;
use App\Models\Publications\Policy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\StudentNote;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Students\StudentAvailabilitySubmission;

class TrainingPortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        //Is the user a student who needs to submit availability?
        if ($user->studentProfile && $user->studentProfile->current && count($user->studentProfile->availability) < 1) {
            return view('training.portal.submit-availability');
        }

        return view('training.portal.index');
    }

    public function submitAvailabilityPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'submission.required' => 'Please enter your availability in the form.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'submission' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'submitAvailabilityErrors');
        }

        //Create submission obj
        $submission = new StudentAvailabilitySubmission([
            'student_id' => auth()->user()->studentProfile->id,
            'submission' => $request->get('submission'),
        ]);
        $submission->save();

        //If they have the "Not Ready" label, process them as new student submitting availability
        $student = auth()->user()->studentProfile;
        foreach ($student->labels as $label) {
            //Find Not Ready label
            if (strtolower($label->label()->name) == 'not ready') {
                //Remove label
                $label->delete();

                //Find Ready For Pick-Up label
                $readyForPickUp = StudentStatusLabel::whereName('Ready For Pick-Up')->first();

                //Assign it with link
                $link = new StudentStatusLabelLink([
                    'student_id' => $student->id,
                    'student_status_label_id' => $readyForPickUp->id,
                ]);
                $link->save();

                //Discord notification in instructors channel
                $discord = new DiscordClient();
                $discord->sendMessageWithEmbed(intval(config('services.discord.instructors')), 'A new student is available for pick-up by an Instructor', $student->user->fullName('FLC') . ' is available to be picked up by an instructor!');

                //Break
                break;
            }
        }

        //Return
        return redirect()->route('training.portal.index')->with('success', 'Thank you for submitting your availability, ' . auth()->user()->fullName('F') . '!');
    }

    public function helpPolicies()
    {
        //Get all training policies
        $policies = Policy::cursor()->filter(function ($p) {
            return in_array($p->title, ['Training', 'Controller', 'Currency']);
        });

        //Return view
        return view('training.portal.help-policies', compact('policies'));
    }

    public function readAcknowledgement(Announcement $announcement)
    {
        ControllerAcknowledgement::create([
            'user_id' => auth()->id(),
            'announcement_id' => $announcement->id
        ]);

        return back()->withSuccess('Acknowledgement read');
    }

    public function viewAvailability()
    {
        //get their availability
        $availability = auth()->user()->studentProfile->availability;

        //Return view
        return view('training.portal.availability', compact('availability'));
    }

    public function yourProgress()
    {
        //Get their student profile
        $studentProfile = auth()->user()->studentProfile;

        //Return view
        return view('training.portal.progress', compact('studentProfile'));
    }

    public function yourInstructor()
    {
        //Get their instructor
        $instructor = auth()->user()->studentProfile->instructor()->instructor;

        //Return view
        return view('training.portal.your-instructor', compact('instructor'));
    }

    public function yourTrainingNotes()
    {
        //Get their training notes
        $notes = StudentNote::where('staff_only', false)->where('student_id', auth()->user()->studentProfile->id)->get()->sortByDesc('created_at');

        //Get their recommendations
        $recommendations = auth()->user()->studentProfile->recommendations->sortByDesc('created_at');

        //Return view
        return view('training.portal.training-notes', compact('notes', 'recommendations'));
    }

    public function yourSessions()
    {
        //Get their training sessions
        $trainingSessions = auth()->user()->studentProfile->trainingSessions->sortByDesc('scheduled_time');

        //Get their OTS sessions
        $otsSessions = auth()->user()->studentProfile->otsSessions->sortByDesc('scheduled_time');

        //Return view
        return view('training.portal.sessions.index', compact('trainingSessions', 'otsSessions'));
    }

    public function viewTrainingSession($session_id)
    {
        //Get session
        $session = TrainingSession::whereId($session_id)->where('student_id', auth()->user()->studentProfile->id)->firstOrFail();

        //Return view
        return view('training.portal.sessions.view-training-session', compact('session'));
    }

    public function viewOtsSession($session_id)
    {
        //Get session
        $session = OTSSession::whereId($session_id)->where('student_id', auth()->user()->studentProfile->id)->firstOrFail();

        //Return view
        return view('training.portal.sessions.view-ots-session', compact('session'));
    }

    public function actions()
    {
        //return view
        return view('training.portal.actions');
    }
}
