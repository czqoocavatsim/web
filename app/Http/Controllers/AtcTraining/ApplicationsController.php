<?php

namespace App\Http\Controllers\AtcTraining;

use App\Http\Controllers\Controller;
use App\Models\AtcTraining\Application;
use App\Models\Settings\AuditLogEntry;
use App\Models\Settings\CoreSettings;
use App\Mail\ApplicationAcceptedStaffEmail;
use App\Mail\ApplicationAcceptedUserEmail;
use App\Mail\ApplicationDeniedUserEmail;
use App\Mail\ApplicationStartedStaffEmail;
use App\Mail\ApplicationStartedUserEmail;
use App\Mail\ApplicationWithdrawnEmail;
use App\Models\Training\RosterMember;
use App\Models\Users\User;
use App\Models\Users\UserNotification;
use Auth;
use Flash;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Mail;

class ApplicationsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function startApplicationProcess()
    {
        //Get user's rating and array of prohibited ratings
        $ratings = ['INA', 'OBS', 'S1', 'S2', 'S3'];
        $rating = Auth::user()->rating_short;

        //Is there an existing application?
        $existingApplication = Application::where('user_id', Auth::id())->where('status', 0)->first();

        //Check their hours...
        //Download via CURL
        $url = 'https://cert.vatsim.net/cert/vatsimnet/idstatusrat.php?cid='.Auth::id();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        //Create XML
        $xml = simplexml_load_string($output) or abort(500, 'XML error, please report to Webmaster.');
        $user = $xml->user[0];
        $hours = intval($user->C1) + intval($user->C2) + intval($user->C3) + intval($user->I1) + intval($user->I2) + intval($user->I3) + intval($user->SUP) + intval($user->ADM);
        $total = floor($hours);

        //Redirects
        if (in_array($rating, $ratings)) {
            //user is in a prohibited rating
            return view('dashboard.application.start')->with('allowed', 'false');
        } elseif ($total < -50) {
            //user does not have 120 hrs
            return view('dashboard.application.start', compact('total', 'url'))->with('allowed', 'hours');
        } elseif ($existingApplication != null) {
            //user already has an application
            return view('dashboard.application.start')->with('allowed', 'pendingApplication');
        } else {
            //hooray they can apply
            return view('dashboard.application.start')->with('allowed', 'true');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function submitApplication(Request $request)
    {
        //Validate form
        $this->validate($request, [
            'applicant_statement' => 'required|max:250',
        ]);

        //Create model and save it
        $application = new Application();
        $application->application_id = Str::random(8);
        $application->user_id = Auth::id();
        $application->submitted_at = date('Y-m-d H:i:s');
        $application->applicant_statement = $request->get('applicant_statement');
        $application->save();

        //Send new application email to staff
        Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailfirchief)->cc(CoreSettings::where('id', 1)->firstOrFail()->emaildepfirchief)->send(new ApplicationStartedStaffEmail($application));

        //Return user to the applications detail page
        return redirect()->route('application.view', $application->application_id)->with('success', 'Application submitted! It should be processed within 72 hours. If you do not get a response, please send a ticket to the FIR Chief');
    }

    public function withdrawApplication($application_id)
    {
        //Find application from URL params
        $application = Application::where('application_id', $application_id)->firstOrFail();

        //Check if someone is being dumb
        if ($application->user != Auth::user()) {
            abort(403);
        } elseif ($application->status != 0) {
            abort(403, 'You cannot withdraw an already withdrawn or processed application!');
        }

        //Set application to withdrawn status and set processed
        $application->status = 3;
        $application->processed_at = date('Y-m-d H:i:s');
        $application->processed_by = Auth::id();
        $application->save();

        //Notify staff
        Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailfirchief)->cc(CoreSettings::where('id', 1)->firstOrFail()->emaildepfirchief)->send(new ApplicationWithdrawnEmail($application));

        //Return user to applications details page
        return redirect()->route('application.view', $application->application_id)->with('info', 'Application withdrawn.');
    }

    public function viewApplication($application_id)
    {
        //Find application from URL params
        $application = Application::where('application_id', $application_id)->firstOrFail();

        if ($application->user != Auth::user()) {
            abort(403);
        }

        //Return user to applications details page
        return view('dashboard.application.view', compact('application'));
    }

    public function viewApplications()
    {
        //Fetch all applications
        //$applications = Application::where('user_id', Auth::user()->id)->get();
        $applications = Auth::user()->applications;

        //Return user to applications details page
        return view('dashboard.application.list', compact('applications'));
    }
}
