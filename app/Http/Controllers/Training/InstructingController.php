<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Board\BoardList;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Students\Student;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Users\User;
use App\Notifications\Training\Instructing\AddedAsInstructor;
use App\Notifications\Training\Instructing\AddedAsStudent;
use App\Notifications\Training\Instructing\RemovedAsInstructor;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Notifications\Training\Instructing\StudentAssignedToYou;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use RestCord\DiscordClient;

class InstructingController extends Controller
{
    public function calendar()
    {
        //Get all sessions
        $trainingSessions = TrainingSession::with('student.user')->with('instructor.user')->with('position')->get()->sortBy('scheduled_time');
        $otsSessions = OTSSession::with('student.user')->with('instructor.user')->with('position')->get()->sortBy('scheduled_time');

        return view('admin.training.instructing.calendar', compact('trainingSessions', 'otsSessions'));
    }

    public function board()
    {
        //Get all instructors
        $instructors = Instructor::whereCurrent(true)->get();

        //Get all lists
        $lists = StudentStatusLabel::whereName('Ready For Pick-up')->orWhere('name', 'Not Ready')->orWhere('name', 'Solo Certification')->orWhere('name', 'Ready for Assessment')->orWhere('name', 'Inactive')->get();

        //Return view
        return view('admin.training.instructing.board', compact('instructors', 'lists'));
    }

    public function instructors()
    {
        //Get all instructors
        $instructors = Instructor::where('current', true)->get();

        //Return view
        return view('admin.training.instructing.instructors.index', compact('instructors'));
    }

    public function students()
    {
        //Get all students
        $students = Student::whereCurrent(true)->get();

        //Return view
        return view('admin.training.instructing.students.index', compact('students'));
    }

    public function viewInstructor($cid)
    {
        //Get the instructor
        $instructor = Instructor::whereCurrent(true)->where('user_id', $cid)->firstOrFail();

        //Return view
        return view('admin.training.instructing.instructors.instructor', compact('instructor'));
    }

    public function viewStudent($cid)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $cid)->firstOrFail();

        //Get instructors for assignment modal
        $instructors = Instructor::whereCurrent(true)->get();

        //Return view
        return view('admin.training.instructing.students.student', compact('student', 'instructors'));
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
            if ($instructor->current) {
                return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('error', 'This person is already an instructor.');
            }
        }

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.instructors', ['addInstructorModal' => 1])->withInput()->withErrors($validator, 'addInstructorErrors');
        }

        //Create instructor obj
        if ($instructor = Instructor::where('user_id', $request->get('cid'))->first()) {
            $instructor->current = true;
            $instructor->created_at = Carbon::now();
            $instructor->assessor = false;
            $instructor->staff_email = $request->get('staff_email');
            $instructor->save();
        } else {
            $instructor = new Instructor();
            $instructor->user_id = $request->get('cid');
            $instructor->assessor = false;
            $instructor->staff_email = $request->get('staff_email');
            $instructor->save();
        }

        //Give them role
        $instructor->user->assignRole('Training Team');

        //Give them role on Discord if able
        if ($instructor->user->hasDiscord() && $instructor->user->memberOfCzqoGuild()) {
            //Get Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Add instructor role
            $discord->guild->addGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $instructor->user->discord_user_id,
                'role.id' => 482816758185590787
            ]);

            //Add instructor role
            $discord->guild->addGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $instructor->user->discord_user_id,
                'role.id' => 752767906768748586
            ]);
        } else {
            Session::flash('info', 'Unable to assign Discord permissions automatically.');
        }

        //Tell them
        $instructor->user->notify(new AddedAsInstructor());

        //Return view
        return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('success', 'Added!');
    }

    public function addStudent(Request $request)
    {
        //Define validator messages
        $messages = [
            'cid.required' => 'A controller CID is required.',
            'cid.min' => 'CIDs are a minimum of 8 characters.',
            'cid.integer' => 'CIDs must be an integer.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required|integer|min:8',
        ], $messages);

        //If there is no user with this CID....
        $validator->after(function ($validator) use($request) {
            if (!User::where('id', $request->get('cid'))->first()) {
                $validator->errors()->add('cid', 'User with this CID not found.');
            }
        });


        //If they're already an instructor...
        if ($student = Student::where('user_id', $request->get('cid'))->first()) {
            if ($student->current) {
                return redirect()->route('training.admin.instructing.students.view', $student->user_id)->with('error', 'This person is already a student.');
            }
        }

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.students', ['addStudentModal' => 1])->withInput()->withErrors($validator, 'addStudentErrors');
        }

        //Create student
        if ($student = Student::where('user_id', $request->get('cid'))->first()) {
            $student->current = true;
            $student->created_at = Carbon::now();
            $student->save();
        } else {
            $student = new Student();
            $student->user_id = $request->get('cid');
            $student->save();
        }

        //Give role
        $student->user->assignRole('Student');

        //Give Discord role
        if ($student->user->hasDiscord() && $student->user->memberOfCzqoGuild()) {
            //Get Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Remove student role
            $discord->guild->addGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $student->user->discord_user_id,
                'role.id' => 482824058141016075
            ]);
        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically.');
        }

        //Notify
        $student->user->notify(new AddedAsStudent());

        //Return
        return redirect()->route('training.admin.instructing.students.view', $student->user->id)->with('success', 'Student added!');
    }

    public function editInstructor($cid, Request $request)
    {
        //Get the instructor
        $instructor = Instructor::where('user_id', $cid)->firstOrFail();

        //Define validator messages
        $messages = [
            'staff_email.required' => 'Staff email required.',
            'staff_email.email' => 'Staff email must be an email address.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'staff_email' => 'required|email',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.instructors.view', ['cid' => $instructor->user->id, 'addInstructorModal' => 1])->withInput()->withErrors($validator, 'editInstructorErrors');
        }

        //Edit instructor
        $instructor->assessor = $request->get('status');
        $instructor->staff_page_tagline = $request->get('staff_page_tagline');
        $instructor->staff_email = $request->get('staff_email');
        $instructor->save();

        //Return view
        return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('success', 'Edited!');
    }

    public function removeInstructor($cid)
    {
        //Get the instructor
        $instructor = Instructor::where('user_id', $cid)->firstOrFail();

        //If they have students, ask user to do that
        if (count($instructor->studentsAssigned) > 0) {
            return redirect()->back()->with('error', 'This instructor has students assigned to them. Please reassign those students before removing the instructor.');
        }

        //Mark as uncurrent
        $instructor->current = false;

        //Remove permissions
        $instructor->user->removeRole('Training Team');

         //Remove role on Discord if able
         if ($instructor->user->hasDiscord() && $instructor->user->memberOfCzqoGuild()) {
            //Get Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Remove instructor role
            $discord->guild->removeGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $instructor->user->discord_user_id,
                'role.id' => 482816758185590787
            ]);

            //Remove instructor role
            $discord->guild->removeGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $instructor->user->discord_user_id,
                'role.id' => 752767906768748586
            ]);
        } else {
            Session::flash('info', 'Unable to remove Discord permissions automatically.');
        }

        //Tell them
        $instructor->user->notify(new RemovedAsInstructor());

        //Save instructor obj
        $instructor->save();

        //Return
        return redirect()->route('training.admin.instructing.instructors')->with('info', 'Instructor removed.');
    }

    public function removeStudent($cid)
    {
        //Find student
        $student = Student::where('user_id', $cid)->firstOrFail();

        //Make as not current
        $student->current = false;

        //Remove role
        $student->user->removeRole('Student');

        //Remove role on Discord if able
        if ($student->user->hasDiscord() && $student->user->memberOfCzqoGuild()) {
            //Get Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Remove student role
            $discord->guild->removeGuildMemberRole([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $student->user->discord_user_id,
                'role.id' => 482824058141016075
            ]);
        } else {
            Session::flash('info', 'Unable to remove Discord permissions automatically.');
        }

        //notify
        $student->user->notify(new RemovedAsStudent());

        //Save
        $student->save();

        //Remove assignments
        $links = InstructorStudentAssignment::where('student_id', $student->id);
        foreach ($links as $l) {
            $l->delete();
        }

        //Return
        return redirect()->route('training.admin.instructing.students')->with('info', 'Student removed.');
    }

    public function assignStudentToInstructor(Request $request, $student_id)
    {
        //Get the instructor
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //If student already has instructor...
        if ($student->instructor()) {
            return redirect()->route('training.admin.instructing.students.view', ['cid' => $student->user->id, 'assignInstructorModal' => 1])->withInput()->with('error', 'Student is already assigned to an instructor');
        }

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
            return redirect()->route('training.admin.instructing.students.view', ['cid' => $student->user->id, 'assignInstructorModal' => 1])->withInput()->withErrors($validator, 'assignInstructorErrors');
        }

        //Assign student to instructor
        $link = new InstructorStudentAssignment();
        $link->instructor_id = $request->get('instructor_id');
        $link->student_id = $student->id;
        $link->save();

        //Notify instructor
        $instructor = Instructor::whereId($request->get('instructor_id'))->first();
        $instructor->user->notify(new StudentAssignedToYou($student));

        //Return
        return redirect()->route('training.admin.instructing.students.view', $student->user->id)->with('success', 'Assigned to instructor!');
    }
}
