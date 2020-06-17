<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Application;
use App\Models\Training\ApplicationReferee;
use App\Models\Training\ApplicationUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApplicationsController extends Controller
{
    public function apply()
    {
        if (Application::where('user_id', Auth::id())->where('status', 0)->first())
        {
            //redirect
        }

        //Redirect of rating isnt C1
        if (Auth::user()->rating_id < 5)
        {
            return view('training.applications.apply')->with('allowed', 'rating');
        }

        //Check hours of controller

        //Download via CURL
        $url = 'https://api.vatsim.net/api/ratings/' . Auth::id() . '/rating_times/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        //Create json and hours int
        $hoursObj = json_decode($output);
        $hoursTotal = intval($hoursObj->c1) + intval($hoursObj->c2) + intval($hoursObj->c3) + intval($hoursObj->i1) + intval($hoursObj->i2) + intval($hoursObj->i3) + intval($hoursObj->sup) + intval($hoursObj->adm);

        //Redirect if hours aren't 80
        if ($hoursTotal < 80)
        {
            return view('training.applications.apply', compact('hoursTotal'))->with('allowed', 'hours');
        }

        return view('training.applications.apply')->with('allowed', 'true');
    }

    public function applyPost(Request $request)
    {
        $messages = [
            'applicant_statement.required' => 'You need to write why you wish to control at Gander.',
            'refereeName.required' => 'Please provide a name for your referee.',
            'refereeEmail.required' => 'Please provide an email for your referee.',
            'refereePosition.required' => 'Please provide your referee\'s email.'
        ];

        //Validate form
        $validator = Validator::make($request->all(), [
            'applicant_statement' => 'required',
            'refereeName' => 'required',
            'refereeEmail' => 'required|email',
            'refereePosition' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'applicationErrors');
        }

        //Create application and save it
        $application = new Application();
        $application->reference_id = Auth::user()->display_fname[0] . Auth::user()->lname[0] . Str::random(4);
        $application->user_id = Auth::id();
        $application->applicant_statement = $request->get('applicant_statement');
        $application->save();

        //Create referee object
        $referee = new ApplicationReferee([
            'application_id' => $application->id,
            'referee_full_name' => $request->get('refereeName'),
            'referee_email' => $request->get('refereeEmail'),
            'referee_staff_position' => $request->get('refereePosition')
        ]);
        $referee->save();

        //Create processing update
        $processingUpdate = new ApplicationUpdate([
            'application_id' => $application->id,
            'update_title' => 'Sit tight, your application is processing!',
            'update_content' => 'If you do not see an update through email or Discord within 5 days, please contact the (Deputy) FIR Chief.',
            'update_type' => 'positive'
        ]);
        $processingUpdate->save();

        //Redirect
    }
}
