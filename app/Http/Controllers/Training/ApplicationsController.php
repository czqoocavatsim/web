<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Roster\RosterMember;
use App\Models\Settings\CoreSettings;
use App\Models\Training\Application;
use App\Models\Training\ApplicationComment;
use App\Models\Training\ApplicationReferee;
use App\Models\Training\ApplicationUpdate;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Students\Student;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Notifications\Training\Applications\ApplicationAcceptedApplicant;
use App\Notifications\Training\Applications\ApplicationRejectedApplicant;
use App\Notifications\Training\Applications\ApplicationWithdrawnStaff;
use App\Notifications\Training\Applications\NewApplicationStaff;
use App\Notifications\Training\Applications\NewCommentApplicant;
use App\Notifications\Training\Applications\NewCommentStaff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\DiscordClient;

class ApplicationsController extends Controller
{
    public function showAll()
    {
        $applications = auth()->user()->applications->sortByDesc('created_at');

        return view('training.applications.showall', compact('applications'));
    }

    public function apply(Request $request)
    {
        if (!auth()->user()->can('start applications')) {
            abort(403, 'You cannot apply for Gander Oceanic at this time. If this is a mistake, please contact the Deputy OCA Chief.');
        }

        if ($pendingApp = Application::where('user_id', auth()->id())->where('status', 0)->first()) {
            //redirect
            $request->session()->flash('alreadyApplied', 'You already have a pending application for Gander.');

            return redirect()->route('training.applications.show', $pendingApp->reference_id);
        }

        //Redirect of rating isnt C1
        if (auth()->user()->rating_id < 5) {
            return view('training.applications.apply')->with('allowed', 'rating');
        }

        //Check hours of controller

        //Download via CURL
        $url = 'https://api.vatsim.net/v2/members/'.auth()->id().'/stats';
        // $url = 'https://api.vatsim.net/v2/members/1342084/stats';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        //Create json and hours int
        $hoursObj = json_decode($output);
        $hoursTotal = (int)$hoursObj->c1 + (int)$hoursObj->c3 + (int)$hoursObj->i1 + (int)$hoursObj->i3;

        //Redirect if hours aren't 50
        if ($hoursTotal < 50) {
            return view('training.applications.apply', compact('hoursTotal'))->with('allowed', 'hours');
        }

        //Check Shanwick roster
        $shanwickRoster = json_decode(Cache::remember('shanwickroster', 86400, function () {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.vatsim.uk/api/validations?position=EGGX_FSS');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            return $output;
        }));

        foreach ($shanwickRoster->validated_members as $member) {
            if ($member->id == auth()->id()) {
                return view('training.applications.apply')->with('allowed', 'shanwick');
            }
        }

        return view('training.applications.apply')->with('allowed', 'true');
    }

    public function applyPost(Request $request)
    {
        $messages = [
            'applicant_statement.required' => 'You need to write why you wish to control at Gander.',
            'refereeName.required'         => 'Please provide a name for your referee.',
            'refereeEmail.required'        => 'Please provide an email for your referee.',
            'refereePosition.required'     => 'Please provide your referee\'s email.',
        ];

        //Validate form
        $validator = Validator::make($request->all(), [
            'applicant_statement' => 'required',
            'refereeName'         => 'required',
            'refereeEmail'        => 'required|email',
            'refereePosition'     => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'applicationErrors');
        }

        //Create application and save it
        $application = new Application();
        $application->reference_id = auth()->user()->display_fname[0].auth()->user()->lname[0].Str::random(4);
        $application->user_id = auth()->id();
        $application->applicant_statement = $request->get('applicant_statement');
        $application->save();

        //Create referee object
        $referee = new ApplicationReferee([
            'application_id'         => $application->id,
            'referee_full_name'      => $request->get('refereeName'),
            'referee_email'          => $request->get('refereeEmail'),
            'referee_staff_position' => $request->get('refereePosition'),
        ]);
        $referee->save();

        //Create processing update
        $processingUpdate = new ApplicationUpdate([
            'application_id' => $application->id,
            'update_title'   => 'Sit tight! Your application is now pending',
            'update_content' => 'If you do not see an update through email or Discord within 5 days, please contact the Deputy OCA Chief.',
            'update_type'    => 'green',
        ]);
        $processingUpdate->save();

        //Dispatch event
        // Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new NewApplicationStaff($application));
        // Notification::route('mail', CoreSettings::find(1)->emaildepfirchief)->notify(new NewApplicationStaff($application));
        Notification::route('mail', CoreSettings::find(1)->emailcinstructor)->notify(new NewApplicationStaff($application));


        //New Applicant in Instructor Channel
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'New Training Applicant!', $application->user->fullName('FLC').' has just applied to control at Gander Oceanic!
        
        [View their application now](https://ganderoceanic.ca/admin/training/applications/'.$application->reference_id.')');

        //Redirect to application page
        return redirect()->route('training.applications.show', $application->reference_id);
    }

    public function show($reference_id)
    {
        //Find application or fail
        $application = Application::where('reference_id', $reference_id)->firstOrFail();

        //Check if not allowed
        if (Gate::denies('view-application', $application) || auth()->id() != $application->user_id) {
            //Show 404 to not show that the application does exist
            abort(404);
        }

        //Get other objects
        $referees = $application->referees;
        $latestUpdate = $application->updates->sortByDesc('created_at')->first();
        $comments = $application->comments;

        //Redirect
        return view('training.applications.show', compact('application', 'referees', 'latestUpdate', 'comments'));
    }

    public function showUpdates($reference_id)
    {
        //Find application or fail
        $application = Application::where('reference_id', $reference_id)->firstOrFail();

        //Check if not allowed
        if (Gate::denies('view-application', $application)) {
            //Show 404 to not show that the application does exist
            abort(404);
        }

        //Get updates
        $updates = $application->updates->sortByDesc('created_at');

        //Redirect
        return view('training.applications.showupdates', compact('application', 'updates'));
    }

    public function withdraw(Request $request)
    {
        //Validate form
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required',
        ]);

        //If bad, return response
        if ($validator->fails()) {
            return redirect()->back()->with('error-modal', 'There was an error withdrawing your application. Please contact the IT Director.');
        }

        //Check if the application exists
        $application = Application::where('reference_id', $request->get('reference_id'))->firstOrFail();

        if (!$application) {
            //return error
            Log::error('Application withdraw fail (ref #'.$request->get('reference_id').')');

            return redirect()->back()->with('error-modal', 'There was an error withdrawing your application. Please contact the IT Director.');
        }

        //Let's withdraw it then
        $application->status = 3;
        $application->save();

        //Update
        $update = new ApplicationUpdate([
            'application_id' => $application->id,
            'update_title'   => 'Application withdrawn',
            'update_content' => 'You may apply for Gander Oceanic again when you are ready',
            'update_type'    => 'grey',
        ]);
        $update->save();

        //Dispatch event
        Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ApplicationWithdrawnStaff($application));
        Notification::route('mail', CoreSettings::find(1)->emaildepfirchief)->notify(new ApplicationWithdrawnStaff($application));

        //Return
        $request->session()->flash('alreadyApplied', 'Application withdrawn.');

        return redirect()->route('training.applications.show', $application->reference_id);
    }

    public function commentPost(Request $request)
    {
        //Validate form
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required',
            'comment'      => 'required',
        ]);

        //If bad, return response
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error-modal', 'There was an error submitting your comment.');
        }

        //Check if the application exists
        $application = Application::where('reference_id', $request->get('reference_id'))->firstOrFail();
        if (!$application) {
            //return error
            Log::error('Application comment fail (ref #'.$request->get('reference_id').')');

            return redirect()->back()->with('error-modal', 'There was an error commenting. Please contact the IT Director.');
        }

        //How long ago was the last one?
        if ($application->comments->where('user_id', auth()->id())->sortByDesc('created_at')->first() && $application->comments->where('user_id', auth()->id())->sortByDesc('created_at')->first()->created_at->diffInMinutes(Carbon::now()) < 10) {
            return redirect()->back()->withInput()->with('error-modal', 'You can only submit a comment every 10 minutes to prevent spam.');
        }

        //Create the comment
        $comment = new ApplicationComment();

        //Assign values
        $comment->user_id = auth()->id();
        $comment->content = $request->get('comment');
        $comment->application_id = $application->id;

        //Save it
        $comment->save();

        //Notify staff
        Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new NewCommentStaff($application, $comment));
        Notification::route('mail', CoreSettings::find(1)->emaildepfirchief)->notify(new NewCommentStaff($application, $comment));

        //Return
        $request->session()->flash('alreadyApplied', 'Comment added!');

        return redirect()->route('training.applications.show', $application->reference_id);
    }

    /*
    Admin
    */
    public function admin()
    {
        //Get all applications and sort into lists
        $applications = Application::where('status', 0)->get()->sortByDesc('created_at');

        //Return the view
        return view('admin.training.applications.index', compact('applications'));
    }

    public function adminProcessedApplications()
    {
        //Get processed applications
        $applications = Application::where('status', 1)->orWhere('status', 2)->get()->sortByDesc('created_at');

        //return the view
        return view('admin.training.applications.processed', compact('applications'));
    }

    public function adminWithdrawnApplications()
    {
        //Get processed applications
        $applications = Application::where('status', 3)->get()->sortByDesc('created_at');

        //return the view
        return view('admin.training.applications.withdrawn', compact('applications'));
    }

    public function adminViewApplication($reference_id)
    {
        //Find application or fail
        $application = Application::where('reference_id', $reference_id)->firstOrFail();

        //Get other objects
        $referees = $application->referees;
        $latestUpdate = $application->updates->sortByDesc('created_at')->first();
        $comments = $application->comments;

        //Check hours of controller

        //Download via CURL
        $url = 'https://api.vatsim.net/v2/members/'.$application->user_id.'/stats';
        // $url = 'https://api.vatsim.net/v2/members/1342084/stats';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        //Create json and hours int
        $hoursObj = json_decode($output);
        $hoursTotal = intval($hoursObj->c1) + intval($hoursObj->c2) + intval($hoursObj->c3) + intval($hoursObj->i1) + intval($hoursObj->i2) + intval($hoursObj->i3) + intval($hoursObj->sup) + intval($hoursObj->adm);

        //Redirect
        return view('admin.training.applications.view', compact('application', 'referees', 'latestUpdate', 'comments', 'hoursTotal', 'hoursObj'));
    }

    public function admincommentPost(Request $request)
    {
        //Validate form
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required',
            'comment'      => 'required',
        ]);

        //If bad, return response
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error-modal', 'There was an error submitting your comment.');
        }

        //Check if the application exists
        $application = Application::where('reference_id', $request->get('reference_id'))->firstOrFail();
        if (!$application) {
            //return error
            Log::error('Application comment fail (ref #'.$request->get('reference_id').')');

            return redirect()->back()->with('error-modal', 'There was an error commenting.');
        }

        //Create the comment
        $comment = new ApplicationComment();

        //Assign values
        $comment->user_id = auth()->id();
        $comment->content = $request->get('comment');
        $comment->application_id = $application->id;

        //Save it
        $comment->save();

        //Notify user
        $application->user->notify(new NewCommentApplicant($application, $comment));

        //Return
        $request->session()->flash('alreadyApplied', 'Comment added!');

        return redirect()->route('training.admin.applications.view', $application->reference_id);
    }

    public function adminAcceptApplication($reference_id, Request $request)
    {
        //Find application or fail
        $application = Application::where('reference_id', $reference_id)->firstOrFail();

        //Set status
        $application->status = 1;
        $application->save();

        // Create update
        $update = new ApplicationUpdate([
            'application_id' => $application->id,
            'update_title'   => 'Your application has been accepted!',
            'update_content' => 'Congratulations! Check out the myCZQO Training portal for more information on how to continue with getting your Gander Oceanic certification.',
            'update_type'    => 'green',
        ]);
        $update->save();

        //Create roster object
        if (!RosterMember::where('cid', $application->user_id)->first()) {
            $rosterMember = new RosterMember();
        } else {
            $rosterMember = RosterMember::where('cid', $application->user_id)->first();
        }

        //Setup roster member
        $rosterMember->cid = $application->user_id;
        $rosterMember->user_id = $application->user_id;
        $rosterMember->certification = 'training';
        $rosterMember->active = 1;
        $rosterMember->save();

        //Create student
        if ($student = Student::where('user_id', $application->user_id)->first()) {
            $student->current = true;
            $student->created_at = Carbon::now();
            $student->save();
        } else {
            $student = new Student();
            $student->user_id = $application->user_id;
            $student->save();
        }

        //Give role
        $student->user->assignRole('Student');

        //Discord Updates
        if ($student->user->hasDiscord() && $student->user->member_of_czqo) {
            //Get Discord client
            $discord = new DiscordClient();

            //Add student discord role
            $discord->assignRole($student->user->discord_user_id, 482824058141016075);

            //Create Instructor Thread
            $discord->createTrainingThread($student->user->fullName('FLC'), '<@'.$student->user->discord_user_id.'>');

            // Notify Senior Team that the application was accepted.
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'Accepted Applicant', $student->user->fullName('FLC').' has just been accepted.', 'error');
        
        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically, as the member is not in the Discord.');

            //Get Discord client
            $discord = new DiscordClient();
            
            // Notify Senior Team that new Applicant is not a member of the Discord Server
            $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'Accepted applicant not in the discord', $student->user->fullName('FLC').' is not a member of Gander Oceanic. They will need to be contacted via email.', 'error');
        }

        //Status label
        $label = new StudentStatusLabelLink([
            'student_status_label_id' => StudentStatusLabel::whereName('Awaiting Exam')->first()->id,
            'student_id'              => $student->id,
        ]);
        $label->save();

        //Change their user role
        $application->user->removeRole('Guest');
        $application->user->assignRole('Student');

        //Notify user
        $application->user->notify(new ApplicationAcceptedApplicant($application));

        //Notify staff
        //Notification::route('mail', CoreSettings::find(1)->emailcinstructor)->notify(new ApplicationAcceptedStaff($application));

        //Return
        $request->session()->flash('alreadyApplied', 'Accepted!');

        return redirect()->route('training.admin.applications.view', $application->reference_id);
    }

    public function adminRejectApplication($reference_id, Request $request)
    {
        //Find application or fail
        $application = Application::where('reference_id', $reference_id)->firstOrFail();

        //Set status
        $application->status = 2;
        $application->save();

        //Create update
        $update = new ApplicationUpdate([
            'application_id' => $application->id,
            'update_title'   => 'Your application has been rejected',
            'update_content' => 'Your application for Gander Oceanic has been rejected. This may be because you do not meet the requirements as per our General Policy. You can view the exact reason for rejection by viewing the comments below.',
            'update_type'    => 'red',
        ]);
        $update->save();

        // Notify Senior Team that new Applicant has been rejected.
        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.applications')), 'Application Denied', $student->user->fullName('FLC')."'s application has been denied.", 'error');

        //Notify user
        $application->user->notify(new ApplicationRejectedApplicant($application));

        //Return
        $request->session()->flash('alreadyApplied', 'Rejected');

        return redirect()->route('training.admin.applications.view', $application->reference_id);
    }
}
