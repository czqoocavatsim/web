<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Roster\RosterMember;
use App\Models\Roster\SoloCertification;
use App\Notifications\Training\SoloCertifications\SoloCertGranted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class SoloCertificationsController extends Controller
{
    public function public()
    {
        //Get solo certs
        $certs = SoloCertification::where('expires', '>', Carbon::now())->get();

        //Return view
        return view('roster.solocerts', compact('certs'));
    }

    public function admin()
    {
        //Get solo certs
        $certs = SoloCertification::where('expires', '>', Carbon::now())->get();

        //get possible controllers
        $trainingControllers = RosterMember::where('certification', 'training')->get();

        //Return view
        return view('admin.training.solocertifications.index', compact('certs', 'trainingControllers'));
    }

    public function addSoloCertificationPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'roster_member.required' => 'A roster member is required.',
            'expires.required' => 'Expiry date required'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'roster_member' => 'required',
            'expires' => 'required'
        ], $messages);

        //If they already have an active solo cert...
        $validator->after(function ($validator) use ($request) {
            if ($member = RosterMember::whereId($request->get('roster_member'))->first()) {
                if ($member->activeSoloCertification()) {
                    $validator->errors()->add('roster_member', 'Roster member already has an active solo certification. Please extend that one.');
                }
            }
        });

        //redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.solocertifications', ['addSoloCert' => 1])->withInput()->withErrors($validator, 'addSoloCertErrors');
        }

        //Create the object
        $cert = new SoloCertification();

        //Assign values
        $cert->roster_member_id = $request->get('roster_member');
        $cert->expires = $request->get('expires');
        $cert->remarks = $request->get('remarks');
        $cert->instructor_id = Auth::id();

        //Save
        $cert->save();

        //Notify
        Notification::send($cert->rosterMember->user, new SoloCertGranted($cert));

        //Redirect
        //return redirect()->route('training.admin.solocertifications.view', compact('cert'));
        return redirect()->route('training.admin.solocertifications');
    }
}
