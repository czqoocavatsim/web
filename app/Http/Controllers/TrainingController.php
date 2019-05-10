<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Flash;
use Calendar;
use Mail;
use Carbon\Carbon;
use App\{InstructingSession,
    Mail\ApplicationAcceptedStaffEmail,
    Mail\ApplicationDeniedUserEmail,
    Mail\ApplicationStartedUserEmail,
    RosterMember,
    Mail\ApplicationWithdrawnEmail,
    AuditLogEntry,
    User,
    Application,
    Mail\ApplicationStartedStaffEmail,
    Mail\ApplicationAcceptedUserEmail,
    CoreSettings,
    UserNotification,
    Instructor,
    Student};

class TrainingController extends Controller
{
    public function index()
    {
        //Check if user is an instructor or director+
        if (Auth::user()->permissions >= 2)
        {
            $yourStudents = null;
            if (Auth::user()->instructorProfile) {
                $yourStudents = Auth::user()->instructorProfile->students;
            }
            $sessions = InstructingSession::all();
            $cal_events = [];
            foreach ($sessions as $session)
            {
                $cal_events[] = \Calendar::event(
                    $session->student->user->fullName('FLC').' | '.$session->type, false,
                    $session->start_time, $session->end_time, $session->id,
                    [
                        'url' => 'https://google.com'
                    ]);
            }
            $calendar = \Calendar::addEvents($cal_events);
            return view('dashboard.training.indexinstructor', compact('yourStudents', 'calendar'));
        }
        else 
        {
            return view('dashboard.training.indexstudent');
        }
    }

    public function instructorsIndex()
    {
        $instructors = Instructor::all();
        return view('dashboard.training.instructors.index', compact('instructors'));
    }

    public function addInstructor(Request $request)
    {
        //Check if the CID entered is a user
        $user = User::whereId($request->get('cid'))->first();

        if ($user)
        {
            if (Instructor::where('user_id', $user->id)->first())
            {
                return redirect()->back()->withInput()->with('error', 'This person is already an instructor.');
            }
            $instructor = new Instructor([
                'user_id' => $user->id,
                'qualification' => $request->get('qualification'),
                'email' => $request->get('email')
            ]);
            $instructor->save();
            AuditLogEntry::insert(Auth::user(), 'Added instructor '.$instructor->user->id.' ('.$instructor->id.')', User::find(1), 0);
            return redirect()->route('training.instructors')->with('success', 'Instructor added!');
        }
        else
        {
            return redirect()->route('training.instructors')->with('error', 'Invalid CID!');
        }
    }

    public function currentStudents()
    {
        $students = Student::whereStatus(0)->get();
        $instructors = Instructor::all();
        return view('dashboard.training.students.current', compact('students', 'instructors'));
    }

    public function viewStudent($id)
    {
        $student = Student::whereId($id)->firstOrFail();
        $instructors = Instructor::all();
        return view('dashboard.training.students.viewstudent', compact('student', 'instructors'));
    }

    public function assignInstructorToStudent(Request $request, $id)
    {
        //Validate
        $this->validate($request, [
            'instructor' => 'required|not_in:0',
        ]);

        //Get student
        $student = Student::whereId($id)->firstOrFail();

        //If its an unassign or not
        if ($request->get('instructor') == 'unassign')
        {
            if ($student->instructor)
            {
                UserNotification::send($student->instructor->user, 'You have been unassigned from '.$student->user->fullName('FLC').'.', route('training.students.view', $student->id));
            }
            $student->instructor_id = null;
            $student->save();
            return redirect()->back()->with('success', 'Instructor unassigned.');
        }

        //Get instructor
        $instructor = Instructor::whereId($request->get('instructor'))->firstOrFail();

        //what if they're already assigned??
        if ($student->instructor !== null && $student->instructor == $instructor)
        {
            return back()->with('error', 'This instructor is already assigned.');
        }

        //notify old instructor if they exist
        if ($student->instructor)
        {
            UserNotification::send($student->instructor->user, 'You have been unassigned from '.$student->user->fullName('FLC').'.', route('training.students.view', $student->id));
        }

        //assign new one
        $student->instructor_id = $instructor->id;
        $student->save();

        //Notify them
        UserNotification::send($instructor->user, 'You have been assign to '.$student->user->fullName('FLC').'.', route('training.students.view', $student->id));
    
        //return
        return redirect()->back()->with('success', 'Instructor assigned.');
    }

    public function changeStudentStatus(Request $request, $id)
    {  
        //Validate
        $this->validate($request, [
            'status' => 'required|not_in:0',
        ]);

        //Get student
        $student = Student::whereId($id)->firstOrFail();

        //Assign new status
        switch ($request->get('status'))
        {
            case '1':
                if ($student->status == 0) { return redirect()->back()->with('error', 'The student is already on this status'); }
                $student->status = 0;
                $student->save();
                UserNotification::send($student->user, 'Your training status has been changed. View it here.', 'https://google.com');
                return redirect()->back()->with('success', 'Status set!');
                break;
            case '2':
                if ($student->status == 1) { return redirect()->back()->with('error', 'The student is already on this status'); }
                $student->status = 1;
                $student->save();
                UserNotification::send($student->user, 'Your training status has been changed. View it here.', 'https://google.com');

                return redirect()->back()->with('success', 'Status set!');
                break;
            case '3':
                if ($student->status == 2) { return redirect()->back()->with('error', 'The student is already on this status'); }
                $student->status = 2;
                $student->save();
                UserNotification::send($student->user, 'Your training status has been changed. View it here.', 'https://google.com');

                return redirect()->back()->with('success', 'Status set!');
                break;
            case '4':
                if ($student->status == 3) { return redirect()->back()->with('error', 'The student is already on this status'); }
                $student->status = 3;
                $student->save();
                UserNotification::send($student->user, 'Your training status has been changed. View it here.', 'https://google.com');

                return redirect()->back()->with('success', 'Status set!');
                break;  
        }
    }

    public function instructingSessionsIndex()
    {
        $sessions = InstructingSession::all();
        $upcomingSessions = [];
        foreach ($sessions as $session)
        {
            $start = Carbon::parse($session->start_time);
            //a week from now
            $afrn = Carbon::now()->addDays(12);
            if ($afrn->greaterThan($start))
            {
                $session->date = Carbon::parse($session->start_time)->toDateString();
                $session->start_time = Carbon::parse($session->start_time)->toTimeString();
                $session->end_time = Carbon::parse($session->end_time)->toTimeString();
                array_push($upcomingSessions, $session);
            }
        }
        $cal_events = [];
        foreach ($sessions as $session)
        {
            $cal_events[] = \Calendar::event(
                $session->student->user->fullName('FLC').' | '.$session->type, false,
                $session->start_time, $session->end_time, $session->id,
                [
                    'url' => route('training.instructingsessions.viewsession', $session->id)
                ]);
        }
        $calendar = \Calendar::addEvents($cal_events);
        return view('dashboard.training.instructingsessions.index', compact('upcomingSessions', 'sessions', 'calendar'));
    }

    public function createInstructingSession(Request $request)
    {
        $this->validate($request, [
            'student_cid' => 'required',
            'type' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        
    }

    public function viewAllApplications()
    {
        $applicationsPending = Application::where('status', 0)->get();
        $applicationsAccepted = Application::where('status', 2)->get();
        $applicationsDenied = Application::where('status', 1)->get();
        return view('dashboard.training.applications.viewall', compact('applicationsAccepted', 'applicationsDenied', 'applicationsPending'));
    }

    public function viewApplication($application_id)
    {
        $application = Application::where('application_id', $application_id)->firstOrFail();
        return view('dashboard.training.applications.viewapplication', compact('application'));
    }

    public function editStaffComment(Request $request, $application_id)
    {
        //Validate
        $this->validate($request, [
            'staff_comment' => 'required',
        ]);

        //Find application from URL params
        $application = Application::where('application_id', $application_id)->firstOrFail();

        //Set application staff comment
        $application->staff_comment = $request->get('staff_comment');
        $application->save();

        //Return user to applications details page
        return redirect()->route('training.viewapplication', $application->application_id)->with('success', 'Staff comment saved!');
    }

    public function acceptApplication($application_id)
    {
        //Find application from URL params
        $application = Application::where('application_id', $application_id)->firstOrFail();

        //Check if someone is being dumb
        if ($application->status != 0)
        {
            abort(403, "You cannot accept an already processed application!");
        }

        //Set application to accepted status and set processed
        $application->status = 2;
        $application->processed_at = date('Y-m-d H:i:s');
        $application->processed_by = Auth::id();
        $application->save();

        //Is user already on roster before adding them?
        if (RosterMember::where('cid', $application->user->id)->first())
        {
            $controller = RosterMember::where('cid', $application->user->id)->first();
            $controller->status = "training";
            $controller->active = 1;
            $controller->save();
        } 
        else 
        {
            //Add user to roster
            $controller = new RosterMember([
                'cid' => $application->user->id,
                'user_id' => $application->user->id,
                'full_name' => $application->user->fullName('FL'),
                'rating' => $application->user->rating_short,
                'division' => $application->user->division_code,
                'status' => 'training',
                'active' => 1
            ]);
            $controller->save(); 
        }
        
        //Is user already a student somehow?
        if (Student::where('user_id', $application->user->id)->first())
        {
            $student = Student::where('user_id', $application->user->id)->first();
            $student->status = 0;
            $student->last_status_change = date('Y-m-d H:i:s');
            $student->accepted_application = $application->id;  
            $student->save();
        }
        else
        {
            $student = new Student([
                'user_id' => $application->user->id,
                'accepted_application' => $application->id
            ]);
            $student->save();
        }

        //Audit log
        AuditLogEntry::insert(Auth::user(), "Application #".$application->application_id.' accepted and controller added to roster, status training', $application->user, 0);

        //Notify staff
        //Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailcinstructor)->send(new ApplicationAcceptedStaffEmail($application));

        //Notify user
        //Mail::to($application->user->email)->send(new ApplicationAcceptedUserEmail($application));
        UserNotification::send($application->user, 'Your application has been accepted, congratulations!', route('application.view', $application->application_id));

        //Return user to applications details page
        return redirect()->route('training.viewapplication', $application->application_id)->with('success', 'Application accepted.');
    }

    public function denyApplication($application_id)
    {
        //Find application from URL params
        $application = Application::where('application_id', $application_id)->firstOrFail();

        //Check if someone is being dumb
        if ($application->status != 0)
        {
            abort(403, "You cannot accept an already processed application!");
        }

        //Set application to denied status and set processed
        $application->status = 1;
        $application->processed_at = date('Y-m-d H:i:s');
        $application->processed_by = Auth::id();
        $application->save();

        //Audit log
        AuditLogEntry::insert(Auth::user(), "Application #".$application->application_id.' denied', $application->user, 0);
 
        //Notify user
        //Mail::to($application->user->email)->send(new ApplicationDeniedUserEmail($application));
        UserNotification::send($application->user, 'Your application has been denied', route('application.view', $application->application_id));

        //Return user to applications details page
        return redirect()->route('training.viewapplication', $application->application_id)->with('info', 'Application denied.');
    }
}
