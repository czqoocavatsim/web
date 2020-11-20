<?php

namespace App\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use App\Models\Network\SessionLog;
use App\Models\News\HomeNewControllerCert;
use App\Models\Roster\RosterMember;
use App\Models\Roster\SoloCertification;
use App\Models\Users\User;
use App\Notifications\Roster\RemovedFromRoster;
use App\Notifications\Roster\RosterStatusChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RosterController extends Controller
{
    public function publicRoster()
    {
        //Get roster
        $roster = RosterMember::where('certification', '!=', 'not_certified')->get();

        //Return view
        return view('roster.index', compact('roster'));
    }

    public function admin()
    {
        //Get the roster
        $roster = RosterMember::all();

        //Return view
        return view('admin.training.roster.index', compact('roster'));
    }

    public function addRosterMemberPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'cid.required' => 'A controller CID is required.',
            'cid.min' => 'CIDs are a minimum of 8 characters.',
            'cid.integer' => 'CIDs must be an integer.',
            'certification.required' => 'Certification required.',
            'active.required' => 'Active required.',
            'date_certified.required' => 'Certification date required.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required|integer|min:8',
            'certification' => 'required',
            'active' => 'required',
            'date_certified' => 'required'
        ], $messages);

        //If there is already someone with this CID...
        $validator->after(function ($validator) use($request) {
            if (RosterMember::where('cid', $request->get('cid'))->first()) {
                $validator->errors()->add('cid', 'CID already on roster');
            }
        });

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.roster', ['addRosterMemberModal' => 1])->withInput()->withErrors($validator, 'addRosterMemberErrors');
        }

        //Create the object
        $rosterMember = new RosterMember();

        //Assign values
        $rosterMember->cid = $request->get('cid');
        $rosterMember->certification = $request->get('certification');
        $rosterMember->active = ($request->get('active') === 'true');
        $rosterMember->remarks = $request->get('remarks');

        //User associated
        $user = User::whereId($request->get('cid'))->first();
        if ($user) {
            $rosterMember->user_id = $user->id;
        } else {
            $rosterMember->user_id = 2;
        }

        //Date certified and roles
        switch ($request->get('certification')) {
            case 'certified':
                $rosterMember->date_certified = $request->get('date_certified');
                $user->assignRole('Certified Controller');
                $user->removeRole('Guest');
                $user->removeRole('Trainee');
            break;
            case 'training':
                $user->removeRole('Guest');
                $user->assignRole('Trainee');
            break;
        }

        //Save
        $rosterMember->save();

        //Notify
        if ($user) {
            Notification::send($user, new RosterStatusChanged($rosterMember));
        }

        //Redirect
        return view('admin.training.roster.controller', compact('rosterMember'));
    }

    public function viewRosterMember($cid)
    {
        //Get roster member
        $rosterMember = RosterMember::where('cid', $cid)->firstOrFail();

        //Return view
        return view('admin.training.roster.controller', compact('rosterMember'));
    }

    public function removeRosterMember($cid)
    {
        //Get roster member
        $rosterMember = RosterMember::where('cid', $cid)->firstOrFail();
        $user = $rosterMember->user;

        //Delete and its dependencies
        foreach (SoloCertification::where('roster_member_id', $rosterMember->id)->get() as $cert) {
            $cert->delete();
        }
        foreach (SessionLog::where('roster_member_id', $rosterMember->id)->get() as $session) {
            $session->roster_member_id = 1;
            $session->save();
        }
        $rosterMember->delete();

        //Roles
        $user->removeRole('Certified Controller');
        $user->assignRole('Guest');
        $user->removeRole('Trainee');

        //Notify user
        Notification::send($user, new RemovedFromRoster($user));

        //Return view
        return redirect()->route('training.admin.roster')->with('info', 'Roster member removed');
    }


    public function editRosterMemberPost($cid, Request $request)
    {
        //Get roster member
        $rosterMember = RosterMember::where('cid', $cid)->firstOrFail();

        //Define validator messages
        $messages = [
            'certification.required' => 'Certification required.',
            'active.required' => 'Active required.',
            'date_certified.required' => 'Certification date required.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'certification' => 'required',
            'active' => 'required',
            'date_certified' => 'required'
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('training.admin.roster.viewcontroller', ['editRosterMemberModal' => 1], compact('rosterMember'))->withInput()->withErrors($validator, 'editRosterMemberErrors');
        }

        //Assign values
        $rosterMember->certification = $request->get('certification');
        $rosterMember->active = ($request->get('active') === 'true');
        $rosterMember->remarks = $request->get('remarks');

        //User
        $user = User::whereId($rosterMember->user->id)->first();

        //Date certified
        switch ($request->get('certification')) {
            case 'certified':
                $rosterMember->date_certified = $request->get('date_certified');
                $user->assignRole('Certified Controller');
                $user->removeRole('Guest');
                $user->removeRole('Trainee');
            break;
            case 'training':
                $user->removeRole('Guest');
                $user->assignRole('Trainee');
            break;
        }

        //Notify
        if ($rosterMember->isDirty('certification') || $rosterMember->isDirty('active')) {
            if ($user) {
                Notification::send($user, new RosterStatusChanged($rosterMember));
            }
        }

        //Save
        $rosterMember->save();

        //Redirect
        return view('admin.training.roster.controller', compact('rosterMember'))->with('success', 'Edited!');
    }

    public function exportRoster()
    {
        //Http headers
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=roster-".Carbon::now()->toDateString().".csv",
        );

        //Get the roster
        $roster = RosterMember::all();

        //Columns
        $columns = array('cid','name','rating','division','certification','active','email');

        //Create the CSV file
        $callback = function() use ($roster, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($roster as $r) {
                fputcsv($file, array(
                    $r->cid,
                    $r->user->fullName('FL'),
                    $r->user->ratings_short,
                    $r->user->division_code,
                    $r->certification,
                    $r->active,
                    Auth::user()->can('view user details') ? $r->email : 'REDACTED'
                ));

                fclose($file);
            }
        };

        //Return
        return response()->stream($callback, 200, $headers);
    }

    public function homePageNewControllers()
    {
        //Get them
        $entries = HomeNewControllerCert::all()->sortByDesc('id');

        //Return view
        return view('admin.training.roster.home-page-new-controllers', compact('entries'));
    }

    public function homePageNewControllersRemoveEntry(Request $request)
    {
        //Validate
        $validator = Validator::make($request->all(), [
            'entry_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        $entry = HomeNewControllerCert::whereId($request->get('entry_id'))->firstOrFail();
        $entry->delete();

        //Return
        return response()->json(['message' => 'Saved'], 200);
    }

    public function homePageNewControllersAddEntry(Request $request)
    {
        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        //See if the controller exists
        if (!User::whereId($request->get('cid'))->first()) {
            return response()->json(['message' => 'No such user found'], 400);
        }

        $entry = new HomeNewControllerCert();
        $entry->controller_id = $request->get('cid');
        $entry->user_id = Auth::id();
        $entry->timestamp = Carbon::now();
        $entry->save();

        //Return
        return response()->json(['message' => 'Saved'], 200);
    }
}
