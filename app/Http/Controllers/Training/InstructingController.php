<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Instructor;
use App\Models\Training\Instructing\OTSSession;
use App\Models\Training\Instructing\TrainingSession;
use App\Models\Users\User;
use App\Notifications\Training\Instructing\AddedAsInstructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function addInstructor(Request $request)
    {
        //Define validator messages
        $messages = [
            'cid.required' => 'A controller CID is required.',
            'cid.min' => 'CIDs are a minimum of 8 characters.',
            'cid.integer' => 'CIDs must be an integer.',
            'staff_email.required' => 'Staff email required.',
            'staff_email.email' => 'Staff email must be an email address.',
            'date_certified.required' => 'Certification date required.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required|integer|min:8',
            'staff_email' => 'required|email',
        ], $messages);

        //If there is no user with this CID....
        $validator->after(function ($validator) use($request) {
            if (!User::where('id', $request->get('cid'))->first()) {
                $validator->errors()->add('cid', 'User with this CID not found.');
            }
        });


        //If they're already an instructor...
        if ($instructor = Instructor::where('user_id', $request->get('cid'))->first()) {
            return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('error', 'This person is already an instructor.');
        }

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.instructors', ['addInstructorModal' => 1])->withInput()->withErrors($validator, 'addInstructorErrors');
        }

        //Create instructor obj
        $instructor = new Instructor();
        $instructor->user_id = $request->get('cid');
        $instructor->assessor = false;
        $instructor->staff_email = $request->get('staff_email');
        $instructor->save();

        //Give them role
        $instructor->user->assignRole('Training Team');

        //Tell them
        $instructor->user->notify(new AddedAsInstructor());

        //Return view
        return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('success', 'Added!');
    }
}
