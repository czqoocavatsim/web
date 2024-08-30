<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Roster\RosterMember;
use App\Models\News\HomeNewControllerCert;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Records\InstuctorRecommendation;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\Student;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Users\User;
use App\Notifications\Training\Instructing\AddedAsInstructor;
use App\Notifications\Training\Instructing\AddedAsStudent;
use App\Notifications\Training\Instructing\RemovedAsInstructor;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Notifications\Training\Instructing\StudentAssignedToYou;
use App\Notifications\Training\Instructing\StudentRecommendedForAssessment;
use App\Notifications\Training\Instructing\StudentRecommendedForSoloCert;
use App\Notifications\Roster\RosterStatusChanged;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Services\DiscordClient;

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
        $lists = StudentStatusLabel::whereName('Ready For Pick-up')->orWhere('name', 'Awaiting Exam')->orWhere('name', 'Solo Certification')->orWhere('name', 'Ready for Assessment')->orWhere('name', 'Inactive')->get();

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
        $students = Student::whereCurrent(true)->orderBy('created_at', 'asc')->get();
        $pastStudents = Student::whereCurrent(false)->orderBy('updated_at', 'desc')->get();

        //Return view
        return view('admin.training.instructing.students.index', compact('students', 'pastStudents'));
    }

    public function yourStudents()
    {
        //Get all students assigned to user
        $students = Student::whereCurrent(true)->cursor()->filter(function ($student) {
            if ($student->instructor() && $student->instructor()->instructor == auth()->user()->instructorProfile) {
                return true;
            }

            return false;
        });

        //Return view
        return view('admin.training.instructing.students.your-students', compact('students'));
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
        $student = Student::where('user_id', $cid)->firstOrFail();

        //Get instructors for assignment modal
        $instructors = Instructor::whereCurrent(true)->get();

        //Get labels for assignment modal
        $labels = StudentStatusLabel::cursor()->filter(function ($l) use ($student) {
            if (StudentStatusLabelLink::where('student_id', $student->id)->where('student_status_label_id', $l->id)->first()) {
                return false;
            }

            return true;
        });

        //Return view
        return view('admin.training.instructing.students.student', compact('student', 'instructors', 'labels'));
    }

    public function addInstructor(Request $request)
    {
        //Define validator messages
        $messages = [
            'cid.required'         => 'A controller CID is required.',
            'cid.min'              => 'CIDs are a minimum of 8 characters.',
            'cid.integer'          => 'CIDs must be an integer.',
            'staff_email.required' => 'Staff email required.',
            'staff_email.email'    => 'Staff email must be an email address.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid'         => 'required|integer|min:8',
            'staff_email' => 'required|email',
        ], $messages);

        //If there is no user with this CID....
        $validator->after(function ($validator) use ($request) {
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
        $instructor->user->assignRole('Instructor');

        //Give them role on Discord if able
        try {
            if ($instructor->user->hasDiscord() && $instructor->user->member_of_czqo) {
                //Get Discord client
                $discord = new DiscordClient();

                //Add instructor role
                $discord->assignRole($instructor->user->discord_user_id, 482816758185590787);
                $discord->assignRole($instructor->user->discord_user_id, 752767906768748586);
            } else {
                Session::flash('info', 'Unable to assign Discord permissions automatically.');
            }
        }
        catch (\Exception $e) {
            Session::flash('info', 'Unable to remove Discord permissions automatically.');
        }

        //Tell them
        $instructor->notify(new AddedAsInstructor());

        //Return view
        return redirect()->route('training.admin.instructing.instructors.view', $instructor->user_id)->with('success', 'Added!');
    }

    public function addStudent(Request $request)
    {
        //Define validator messages
        $messages = [
            'cid.required' => 'A controller CID is required.',
            'cid.min'      => 'CIDs are a minimum of 8 characters.',
            'cid.integer'  => 'CIDs must be an integer.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required|integer|min:8',
        ], $messages);

        //If there is no user with this CID....
        $validator->after(function ($validator) use ($request) {
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
        try {
            if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
                //Get Discord client
                $discord = new DiscordClient();

                //Add student role
                $discord->assignRole($student->user->discord_user_id, 482824058141016075);
            } else {
                Session::flash('info', 'Unable to add Discord permissions automatically.');
            }
        } catch (\Exception $e) {

        }

        //Give Awaiting Exam status label
        $label = new StudentStatusLabelLink([
            'student_status_label_id' => StudentStatusLabel::whereName('Awaiting Exam')->first()->id,
            'student_id'              => $student->id,
        ]);
        $label->save();

        //Create roster object
        if (!RosterMember::where('cid', $student->user_id)->first()) {
            $rosterMember = new RosterMember();
        } else {
            $rosterMember = RosterMember::where('cid', $student->user_id)->first();
        }

        //Setup roster member
        $rosterMember->cid = $student->user_id;
        $rosterMember->user_id = $student->user_id;
        $rosterMember->certification = 'training';
        $rosterMember->active = 1;
        $rosterMember->save();

        //Discord Updates
        if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
            //Get Discord client
            $discord = new DiscordClient();

            //Add student discord role
            $discord->assignRole($student->user->discord_user_id, 482824058141016075);

            //Create Instructor Thread
            $discord->createTrainingThread($student->user->fullName('FLC'), '<@'.$student->user->discord_user_id.'>');

            // Notify Senior Team that the application was accepted.
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'Manually Added Student', $student->user->fullName('FLC').' has just been added as a manual student. Their training record has been created automatically.', 'error');
        
        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically, as the member is not in the Discord.');

            //Get Discord client
            $discord = new DiscordClient();
            
            // Notify Senior Team that new Applicant is not a member of the Discord Server
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'Manually Added Student not in Discord', $student->user->fullName('FLC').' is not a member of Gander Oceanic. They will need to be contacted via email.', 'error');
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
            'staff_email.email'    => 'Staff email must be an email address.',
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

        //If assessor, give role, vice versa
        if ($instructor->assessor) {
            $instructor->user->assignRole('Assessor');
        } else {
            $instructor->user->removeRole('Assessor');
        }

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
        $instructor->user->removeRole('Instructor');
        $instructor->user->removeRole('Assessor');

        //Remove role on Discord if able
        try {
            if ($instructor->user->hasDiscord() && $instructor->user->member_of_czqo) {
                //Get Discord client
                $discord = new DiscordClient();

                //Remove instructor role
                $discord->removeRole($instructor->user->discord_user_id, 482816758185590787);
                $discord->removeRole($instructor->user->discord_user_id, 752767906768748586);
            } else {
                Session::flash('info', 'Unable to remove Discord permissions automatically.');
            }
        }
        catch (\Exception $e) {
            Session::flash('info', 'Unable to remove Discord permissions automatically.');
        }

        //Tell them
        $instructor->notify(new RemovedAsInstructor());

        //Save instructor obj
        $instructor->save();

        //Return
        return redirect()->route('training.admin.instructing.instructors')->with('info', 'Instructor removed.');
    }

    public function removeStudent($cid)
    {
        //Find student
        $student = Student::where('user_id', $cid)->firstOrFail();

        // Delete Roster Entry
        $roster = RosterMember::where('cid', $cid)->firstOrFail();
        $roster->delete();

        //Make as not current
        $student->current = false;

        //Remove role
        $student->user->removeRole('Student');

        //Discord Updates
        if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
            //Get Discord client
            $discord = new DiscordClient();

            //remove student discord role
            $discord->removeRole($student->user->discord_user_id, 482824058141016075);

            $discord->EditThreadTag('Inactive', $student->user->id);

            //close Instructor Thread
            $discord->closeTrainingThread($student->user->id, $student->user->discord_user_id, 'cancel');

            // Notify Senior Team that new training has been terminated.
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'Training Terminated', $student->user->fullName('FLC').' has had their training terminated.', 'error');
        
        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically, as the member is not in the Discord.');

            //Get Discord client
            $discord = new DiscordClient();
            
            // Notify Senior Team that training has been terminated
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'Training Terminated', $student->user->fullName('FLC').' has had their training terminated.', 'error');
        }

        //Remove labels and instructor links and availability
        foreach ($student->labels as $label) {
            if (!in_array($label->label()->name, ['Completed'])) {
                $label->delete();
            }
        }

        if ($student->instructor()) {
            $student->instructor()->delete();
        }
        foreach ($student->availability as $a) {
            $a->delete();
        }

        //notify
        $student->user->notify(new RemovedAsStudent());

        //Save
        $student->save();

        //Remove Instructor from Student
        $links = InstructorStudentAssignment::where('student_id', $student->id);
        foreach ($links as $l) {
            $l->delete();
        }

        //Return
        return redirect()->route('training.admin.instructing.students')->with('info', 'Student removed.');
    }

    public function certifyStudent($cid)
    {
        $student = Student::where('user_id', $cid)->firstOrFail();

        //Make as not current
        $student->current = false;
        $student->save();

        // Remove Student Status & Set Controller as Active
        $student->user->removeRole('Student');
        $student->user->assignRole('Certified Controller');
        $student->user->removeRole('Guest');

        // Update Traing Lable
        $link_old = StudentStatusLabelLink::where('student_id', $student->id);
        $link_old->delete();

        $link = new StudentStatusLabelLink([
            'student_id'              => $student->id,
            'student_status_label_id' => StudentStatusLabel::whereName('Completed')->first()->id,
        ]);
        $link->save();

        //Remove labels and instructor links and availability
        foreach ($student->labels as $label) {
            if (!in_array($label->label()->name, ['Completed'])) {
                $label->delete();
            }
        }

        // Unassign Instructor from Student
        $instructor_link = InstructorStudentAssignment::where('student_id', $student->id);
        $instructor_link->delete();

        // Create new certification (for home page)
        $controller_cert = new HomeNewControllerCert([
            'controller_id' => $cid,
            'user_id' => $cid,
            'timestamp' => Carbon::now(),
        ]);
        $controller_cert->save();

        if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
            // Update Thread Tag to match site
            $discord = new DiscordClient();
            $discord->EditThreadTag('Completed', $student->user->id);

            // Close Training Thread Out & Send Completion Message
            $discord = new DiscordClient();
            $discord->closeTrainingThread($student->user->fullName('FLC'), $student->user->discord_user_id, 'certify');
        } else {

        }

        // Update Roster with Certification Status
        $rosterMember = RosterMember::where('cid', $cid)->firstOrFail();

        //Assign values
        $rosterMember->certification = 'Certified';
        $rosterMember->active = 1;
        $rosterMember->remarks = 'Certified on NAT_FSS (Web Message)';
        $rosterMember->date_certified = Carbon::now();

        //User
        $user = User::whereId($rosterMember->user->id)->first();
        

        //Give Discord role
        if ($rosterMember->user->hasDiscord() && $rosterMember->user->member_of_czqo) {
            //Get Discord client
            $discord = new DiscordClient();

            //Get role ID based off status
            $roles = [
                'certified' => 482819739996127259,
                'student' => 482824058141016075,
            ];

            //Add role and remove role
            if ($rosterMember->certification == 'certified') {
                $discord->assignRole($rosterMember->user->discord_user_id, $roles['certified']);
                $discord->removeRole($rosterMember->user->discord_user_id, $roles['student']);
            } elseif ($rosterMember->certification == 'training') {
                $discord->assignRole($rosterMember->user->discord_user_id, $roles['student']);
                $discord->removeRole($rosterMember->user->discord_user_id, $roles['certified']);
            }


        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically.');
        }

        //Notify
        if ($rosterMember->isDirty('certification') || $rosterMember->isDirty('active')) {
            if ($user) {
                Notification::send($user, new RosterStatusChanged($rosterMember));
            }
        }

        //Save
        $rosterMember->save();

        return back()->with('info', $student->user->FullName('FLC').' has been Certified as a Controller!');
    }

    public function assignStudentToInstructor(Request $request, $student_id)
    {
        //Get the instructor
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //If student already has instructor...
        if ($student->instructor() && $student->instructor()->instructor->id == $request->get('instructor_id')) {
            return redirect()->route('training.admin.instructing.students.view', ['cid' => $student->user->id, 'assignInstructorModal' => 1])->withInput()->with('error', 'Student is already assigned to an instructor or you are trying to assign them to who they\'re already assigned to');
        }

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
            return redirect()->route('training.admin.instructing.students.view', ['cid' => $student->user->id, 'assignInstructorModal' => 1])->withInput()->withErrors($validator, 'assignInstructorErrors');
        }

        //Remove existing links
        if ($student->instructor()) {
            $link = InstructorStudentAssignment::where('student_id', $student->id);
            $link->delete();
        }

        //Assign student to instructor
        $link = new InstructorStudentAssignment();
        $link->instructor_id = $request->get('instructor_id');
        $link->student_id = $student->id;
        $link->save();

        //Relabelling process
        foreach ($student->labels as $label) {
            $label->delete();
        }
        
        //Assign it with link
        $link = new StudentStatusLabelLink([
            'student_id'              => $student->id,
            'student_status_label_id' => StudentStatusLabel::whereName('In Progress')->first()->id,
        ]);
        $link->save();

        // Update Thread Tag
        if ($student->user->member_of_czqo) {
            $discord = new DiscordClient();
            $discord->EditThreadTag('In Progress', $student->user->id);
        }

        //Notify instructor
        $instructor = Instructor::whereId($request->get('instructor_id'))->first();
        $instructor->notify(new StudentAssignedToYou($student));

        //Return
        return redirect()->route('training.admin.instructing.students.view', $student->user->id)->with('success', 'Assigned to instructor!');
    }

    public function dropStudentFromInstructor($student_id)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //Find the link
        $link = InstructorStudentAssignment::where('student_id', $student->id)->where('instructor_id', auth()->user()->instructorProfile->id)->firstOrFail();

        //Remove
        $link->delete();

        //Relabelling process
        foreach ($student->labels as $label) {
            $label->delete();
        }

        // Update Thread Tag
        if ($student->user->member_of_czqo) {
            $discord = new DiscordClient();
            $discord->EditThreadTag('Ready For Pick-Up', $student->user->id);
        }

        //Assign it with link
        $link = new StudentStatusLabelLink([
            'student_id'              => $student->id,
            'student_status_label_id' => StudentStatusLabel::whereName('Ready For Pick-Up')->first()->id,
        ]);
        $link->save();

        // Update Thread Tag
        if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
            $discord = new DiscordClient();
            $discord->EditThreadTag('Ready For Pick-Up', $student->user->id);
        }

        //Discord notification in instructors channel
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(intval(config('services.discord.instructors')), 'A new student is available for pick-up by an Instructor', $student->user->fullName('FLC').' is available to be picked up by an instructor!');

        return redirect()->route('training.admin.instructing.students.view', $student->user_id)->with('info', 'Student dropped');
    }

    public function dropStatusLabelFromStudent($student_id, $label_link_id)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //Find the link
        $link = StudentStatusLabelLink::where('student_id', $student->id)->whereId($label_link_id)->firstOrFail();

        //Does user have any labels left?
        if (count($student->labels) == 1) {
            //Tell them to assign another label first
            return redirect()->back()->with('error', 'Please assign another label before deleting this one.');
        }

        //Delete the link
        $link->delete();

        //Return
        return redirect()->back()->with('success', 'Label removed!');
    }

    public function assignStatusLabelToStudent(Request $request, $student_id)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //Validate
        $validator = Validator::make($request->all(), [
            'label_id' => 'required|integer',
        ]);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.instructing.students.view', ['cid' => $student->user->id, 'assignLabelModal' => 1])->withInput()->withErrors($validator, 'assignLabelErrors');
        }

        //Find the label
        $label = StudentStatusLabel::whereId($request->get('label_id'))->firstOrFail();

        //Does user already have this label?
        if (StudentStatusLabelLink::where('student_id', $student->id)->where('student_status_label_id', $label->id)->exists()) {
            //Return error
            return back()->with('error', "Label {$label->name} already assigned.");
        }

        if ($label->name == 'Ready For Pick-Up'){
            //Discord notification in instructors channel
            $discord = new DiscordClient();
            $discord->sendMessageWithEmbed(intval(config('services.discord.instructors')), 'A new student is available for pick-up by an Instructor', $student->user->fullName('FLC') . ' is available to be picked up by an instructor!');
        }

        // Update Thread Tag to match site
        $discord = new DiscordClient();
        $discord->EditThreadTag($label->name, $student->user->id);

        //Create the link
        $link = new StudentStatusLabelLink([
            'student_id'              => $student->id,
            'student_status_label_id' => $label->id,
        ]);
        $link->save();

        //Return
        return redirect()->back()->with('success', 'Label added!');
    }

    public function recommendSoloCertification($student_id)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //Is student on solo cert?
        if ($student->soloCertification()) {
            return redirect()->back()->with('error', 'Student is already on a solo certification. Check if their status labels are correctly setup.');
        }

        //Notify via email
        foreach (Instructor::whereAssessor(true)->whereCurrent(true)->get() as $instructor) {
            $instructor->notify(new StudentRecommendedForSoloCert($student, auth()->user()->instructorProfile));
        }

        //Create object
        $recommendation = new InstuctorRecommendation([
            'student_id'    => $student->id,
            'instructor_id' => auth()->user()->instructorProfile->id,
            'type'          => 'Solo Certification',
        ]);
        $recommendation->save();

        //Return
        return redirect()->back()->with('success', 'Recommendation sent!');
    }

    public function recommendAssessment($student_id)
    {
        //Get the student
        $student = Student::whereCurrent(true)->where('user_id', $student_id)->firstOrFail();

        //Is student already ready for assessment?
        if ($student->hasLabel('Ready for Assessment') || $student->hasLabel('Complete')) {
            return redirect()->back()->with('error', 'Student is already set as ready for assessment. Check if their status labels are correctly setup.');
        }

        //Notify via email
        foreach (Instructor::whereAssessor(true)->whereCurrent(true)->get() as $instructor) {
            $instructor->notify(new StudentRecommendedForAssessment($student, auth()->user()->instructorProfile));
        }

        //Create object
        $recommendation = new InstuctorRecommendation([
            'student_id'    => $student->id,
            'instructor_id' => auth()->user()->instructorProfile->id,
            'type'          => 'Ready For Assessment',
        ]);
        $recommendation->save();

        //Assign ready for assessment label
        $student->assignStatusLabel(StudentStatusLabel::whereName('Ready for Assessment')->first());

        //Remove in progress
        if ($inProgress = StudentStatusLabelLink::where('student_id', $student->id)->where('student_status_label_id', StudentStatusLabel::whereName('In Progress')->first()->id)->first()) {
            $inProgress->delete();
        }

        //Return
        return redirect()->back()->with('success', 'Recommendation sent!');
    }
}
