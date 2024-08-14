<?php

namespace App\Http\Controllers\Roster;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Services\DiscordClient;
use App\Models\News\Announcement;
use App\Models\Network\ShanwickController;
use App\Models\Roster\RosterMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\News\HomeNewControllerCert;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Roster\RemovedFromRoster;
use App\Notifications\Roster\RosterStatusChanged;

class RosterController extends Controller
{
    public function publicRoster()
    {
        // Get CZQO Roster
        $czqo_roster = RosterMember::where('certification', '!=', 'not_certified')
            ->select(['id', 'certification', 'active', 'user_id'])
            ->with('user:id,fname,lname,rating_short,display_fname,display_cid_only,display_last_name,division_name,division_code')
            ->get();

        // Get EGGX Roster
        $shanwick_controllers = ShanwickController::all();

        // Transform EGGX data to Eloquent-like objects
        $eggx_roster = $shanwick_controllers->map(function($controller) {
            return $this->transformShanwickControllerToRoster($controller);
        });

        // Combine Data
        $roster = $czqo_roster->concat($eggx_roster);   

        // return $roster;
        
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
            'date_certified.required' => 'Certification date required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'cid' => 'required|integer|min:8',
            'certification' => 'required',
            'active' => 'required',
            'date_certified' => 'required',
        ], $messages);

        //If there is already someone with this CID...
        $validator->after(function ($validator) use ($request) {
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
                $user->removeRole('Student');
                break;
            case 'training':
                $user->removeRole('Guest');
                $user->assignRole('Student');
                break;
        }

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

        //Save
        $rosterMember->save();

        //Notify
        if ($user) {
            Notification::send($user, new RosterStatusChanged($rosterMember));
        }

        //Redirect
        return redirect()->route('training.admin.roster.viewcontroller', $rosterMember->cid)->with('success', 'Roster Member Added');
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

        $rosterMember->delete();

        //Roles
        $user->removeRole('Certified Controller');
        $user->assignRole('Guest');
        $user->removeRole('Student');

        //Give Discord role
        if ($rosterMember->user->hasDiscord() && $rosterMember->user->member_of_czqo) {
            //Get Discord client
            $discord = new DiscordClient();

            //Get role ID based off status
            $roles = [
                'certified' => 482819739996127259,
                'student' => 482824058141016075,
            ];

            $discord->removeRole($rosterMember->user->discord_user_id, $roles['student']);
            $discord->removeRole($rosterMember->user->discord_user_id, $roles['certified']);
        } else {
            Session::flash('info', 'Unable to add Discord permissions automatically.');
        }

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
            'date_certified.required' => 'Certification date required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'certification' => 'required',
            'active' => 'required',
            'date_certified' => 'required',
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
                $user->removeRole('Student');
                break;
            case 'training':
                $user->removeRole('Guest');
                $user->assignRole('Student');
                break;
        }

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

        //Redirect
        return redirect()->route('training.admin.roster.viewcontroller', $cid)->with('success', 'Edited!');
    }

    public function exportRoster()
    {
        //Http headers
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=roster-' . Carbon::now()->toDateString() . '.csv',
        ];

        //Get the roster
        $roster = RosterMember::all();

        //Columns
        $columns = ['cid', 'name', 'rating', 'division', 'certification', 'active', 'email'];

        //Create the CSV file
        $callback = function () use ($roster, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($roster as $r) {
                fputcsv($file, [
                    $r->cid,
                    $r->user->fullName('FL'),
                    $r->user->ratings_short,
                    $r->user->division_code,
                    $r->certification,
                    $r->active,
                    Auth::user()->can('view user details') ? $r->email : 'REDACTED',
                ]);

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
            'entry_id' => 'required',
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
            'cid' => 'required',
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

    //Controller Acknowledgement
    public function getAcknowledgement(Announcement $announcement)
    {
        return view('admin.training.acknowledgements.acknowledgement', compact('announcement'));
    }

    public function transformShanwickControllerToRoster($shanwickController) {
        $rosterMember = new RosterMember();
        $rosterMember->certification = 'certified';
        $rosterMember->active = 1;
        $rosterMember->eggx = true;
        $rosterMember->user_id = $shanwickController->controller_cid;
        
        // Create a pseudo-user object
        $rosterMember->user = new User();
        $rosterMember->user->id = $shanwickController->controller_cid;
        $rosterMember->user->fname = null;
        $rosterMember->user->lname = null;
        $rosterMember->user->rating_short = $shanwickController->rating;
        $rosterMember->user->display_fname = null;
        $rosterMember->user->display_cid_only = 1;
        $rosterMember->user->display_last_name = 0;
        $rosterMember->user->division_name = null;
        $rosterMember->user->division_code = $shanwickController->division;

        return $rosterMember;
    }
}
