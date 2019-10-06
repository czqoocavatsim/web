<?php

namespace App\Http\Controllers;

use App\AuditLogEntry;
use App\LegacyUser;
use App\Mail\RosterStatusMail;
use App\RosterMember;
use App\User;
use App\UserNotification;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class RosterController extends Controller
{
    public function showPublic()
    {
        $roster = RosterMember::all();

        return view('roster', compact('roster'));
    }

    public function verifyLegacyMember()
    {
        $legacyUser = LegacyUser::where('id', Auth::user()->id)->first();
        if ($legacyUser === null) {
            return view('dashboard.roster.verifylegacy', ['status' => 'notverified']);
        }
        $user = Auth::user();
        $user->permissions = 1;
        $user->save();
        $controller = new RosterMember([
            'cid' => $user->id,
            'user_id' => $user->id,
            'full_name' => $user->fullName('FL'),
            'rating' => $user->rating,
            'division' => $user->division,
        ]);
        if ($legacyUser->certification == 'Certified') {
            $controller->status = 'certified';
        } elseif ($legacyUser->certification == 'Not') {
            $controller->status = 'not_certified';
        } elseif ($legacyUser->certification == 'Instructor') {
            $controller->status = 'instructor';
        } elseif ($legacyUser->certification == 'Training') {
            $controller->status = 'training';
        }
        if ($legacyUser->status == 'Active') {
            $controller->active = 1;
        } else {
            $controller->active = 0;
        }
        $controller->save();
        $audit = new AuditLogEntry([
            'user_id' => 1,
            'affected_id' => $user->id,
            'action' => 'LEGACY ROSTER VERIFIED CONTROLLER, ADDED TO ROSTER ('.$controller->cid.')',
            'time' => date('Y-m-d H:m:i'),
            'private' => 0,
        ]);
        $audit->save();

        return view('dashboard.roster.verifylegacy', ['status' => 'verified']);
    }

    public function index()
    {
        $roster = RosterMember::all();

        return view('dashboard.roster.index', compact('roster'));
    }

    public function viewController($id)
    {
        $controller = RosterMember::where('cid', $id)->firstOrFail();

        return view('dashboard.roster.viewcontroller', compact('controller'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addController(Request $request)
    {
        $validateddata = $request->validate([
            'cid' => 'required|min:6|max:7',
            'full_name' => 'required',
            'rating' => 'required',
            'division' => 'required',
            'status' => 'required',
        ]);

        $controller = null;
        $potentialUser = User::find($request->get('cid'));

        if ($potentialUser != null) {
            $controller = new RosterMember([
                'cid' => $request->get('cid'),
                'user_id' => $potentialUser->id,
                'full_name' => $potentialUser->fullName('FL'),
                'rating' => $potentialUser->rating_short,
                'division' => $potentialUser->division_code,
                'status' => $request->get('status'),
                'active' => $request->get('active'),
            ]);

            if ($request->get('active') == 'yes') {
                $controller->active = 1;
            } else {
                $controller->active = 0;
            }

            $controller->save();

            return redirect()->route('roster.index')->with('success', 'Controller added and matched with user '.$potentialUser->id.'!');
        } else {
            $controller = new RosterMember([
                'cid' => $request->get('cid'),
                'user_id' => 2,
                'full_name' => $request->get('full_name'),
                'rating' => $request->get('rating'),
                'division' => $request->get('division'),
                'status' => $request->get('status'),
            ]);

            if ($request->get('active') == 'yes') {
                $controller->active = 1;
            } else {
                $controller->active = 0;
            }

            if ($controller->user_id != 2) {
                $entry = new AuditLogEntry([
                    'user_id' => Auth::user()->id,
                    'affected_id' => $controller->user_id,
                    'action' => 'ADD ROSTER MEM. ('.$controller->cid.')',
                    'time' => date('Y-m-d H:i:s'),
                    'private' => 0,
                ]);
                $entry->save();
            } else {
                $entry = new AuditLogEntry([
                    'user_id' => Auth::user()->id,
                    'affected_id' => 2,
                    'action' => 'ADD ROSTER MEM. ('.$controller->cid.')',
                    'time' => date('Y-m-d H:i:s'),
                    'private' => 0,
                ]);
                $entry->save();
            }

            $controller->save();

            $notification = new UserNotification([
                'user_id' => $controller->user_id,
                'content' => 'You have been added to the CZQO controller roster! Check your status on the dashboard.',
                'link' => url('/dashboard'),
                'dateTime' => date('Y-m-d H:i:s'),
            ]);
            $notification->save();

            Mail::to(User::find($controller->user_id)->email)->send(new RosterStatusMail($controller));

            return redirect()->route('roster.index')->with('success', 'Controller added!');
        }
    }

    public function editController(Request $request, $cid)
    {
        $validateddata = $request->validate([
            'status' => 'required',
        ]);

        $controller = RosterMember::where('cid', $cid)->firstOrFail();
        $controller->status = $request->get('status');
        if ($controller->user_id == 2) {
            $controller->rating = $request->get('rating');
            if ($request->get('division') === null) {
                return back()->withInput()->with('error', 'Division must not be empty.');
            }
            $controller->division = $request->get('division');
        }
        if ($request->get('active') == 'yes') {
            $controller->active = 1;
        } else {
            $controller->active = 0;
        }
        if ($controller->user_id != 2) {
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => $controller->user_id,
                'action' => 'EDIT ROSTER MEM. ('.$controller->cid.')',
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();
        } else {
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => 2,
                'action' => 'EDIT ROSTER MEM. ('.$controller->cid.')',
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();
        }
        $controller->save();

        $notification = new UserNotification([
            'user_id' => $controller->user_id,
            'content' => 'Your roster status has been changed. Check your status on the dashboard.',
            'link' => url('/dashboard'),
            'dateTime' => date('Y-m-d H:i:s'),
        ]);
        $notification->save();
        Mail::to(User::find($controller->user_id))->send(new RosterStatusMail($controller));

        return back()->with('success', 'Controller updated!');
    }

    public function deleteController($cid)
    {
        $controller = RosterMember::where('cid', $cid)->firstOrFail();
        //Todo: roster removal email
        if ($controller->user_id != 2) {
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => $controller->user_id,
                'action' => 'DELETED FROM ROSTER ('.$controller->cid.')',
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();
        } else {
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => 2,
                'action' => 'DELETED FROM ROSTER ('.$controller->cid.')',
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();
        }
        $controller->delete();
        $notification = new UserNotification([
            'user_id' => $controller->user_id,
            'content' => 'You have been removed from the CZQO controller roster. Check your status on the dashboard.',
            'link' => url('/dashboard'),
            'dateTime' => date('Y-m-d H:i:s'),
        ]);
        $notification->save();
        Mail::to(User::find($controller->user_id))->send(new RosterStatusMail($controller));

        return redirect()->route('roster.index')->with('success', 'Controller deleted!');
    }
}
