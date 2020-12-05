<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Publications\Policy;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Records\StudentNote;
use App\Models\Training\Instructing\Students\StudentAvailabilitySubmission;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RestCord\DiscordClient;

class TrainingPortalController extends Controller
{
    public function index()
    {
        //Is the user a student who needs to submit availability?
        if (Auth::user()->studentProfile && Auth::user()->studentProfile->current && count(Auth::user()->studentProfile->availability) < 1)
        {
            return view('training.portal.submit-availability');
        }

        return view('training.portal.index');
    }

    public function submitAvailabilityPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'submission.required' => 'Please enter your availability in the form.'
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
            'student_id' => Auth::user()->studentProfile->id,
            'submission' => $request->get('submission')
        ]);
        $submission->save();

        //If they have the "Not Ready" label, process them as new student submitting availability
        $student = Auth::user()->studentProfile;
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
                    'student_status_label_id' => $readyForPickUp->id
                ]);
                $link->save();

                //Discord notification in instructors channel
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.instructors')),
                    "content" => "",
                    'embed' => [
                        "title" => "A new student is available for pick-up by an Instructor",
                        "url" => route('training.admin.instructing.students.view', $student->user_id),
                        "timestamp" => Carbon::now(),
                        "color" => hexdec( "2196f3" ),
                        "description" => $student->user->fullName('FLC')
                    ]
                ]);

                //Break
                break;
            }
        }

        //Return
        return redirect()->route('training.portal.index')->with('success', "Thank you for submitting your availability, " . Auth::user()->fullName("F") . '!');
    }

    public function helpPolicies()
    {
        //Get all training policies
        $policies = Policy::cursor()->filter(function ($p) {
            return (in_array($p->title, ['Training', 'Controller', 'Currency']));
        });

        //Return view
        return view('training.portal.help-policies', compact('policies'));
    }

    public function viewAvailability()
    {
        //get their availability
        $availability = Auth::user()->studentProfile->availability;

        //Return view
        return view('training.portal.availability', compact('availability'));
    }

    public function yourProgress()
    {
        //Get their student profile
        $studentProfile = Auth::user()->studentProfile;

        //Return view
        return view('training.portal.progress', compact('studentProfile'));
    }

    public function yourInstructor()
    {
        //Get their instructor
        $instructor = Auth::user()->studentProfile->instructor()->instructor;

        //Return view
        return view('training.portal.your-instructor', compact('instructor'));
    }

    public function yourTrainingNotes()
    {
        //Get their training notes
        $notes = StudentNote::where('staff_only', false)->where('student_id', Auth::user()->studentProfile->id)->get()->sortByDesc('created_at');

        //Get their recommendations
        $recommendations = Auth::user()->studentProfile->recommendations->sortByDesc('created_at');

        //Return view
        return view('training.portal.training-notes', compact('notes', 'recommendations'));
    }

    public function actions()
    {
        //return view
        return view('training.portal.actions');
    }
}
