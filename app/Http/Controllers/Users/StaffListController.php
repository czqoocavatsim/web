<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Users\StaffGroup;
use App\Models\Users\StaffMember;
use App\Models\Users\User;
use Illuminate\Http\Request;

class StaffListController extends Controller
{
    public function index()
    {
        // Senior Leadership List
        $leadership = StaffMember::all();

        // Web Team List
        $web = User::role('Web Team')->get();

        // Events & Marketing List
        $events = User::role('Events and Marketing Team')->get();

        // Instructor Staff List
        $instructors = Instructor::join('users', 'instructors.user_id', '=', 'users.id')
            ->where('instructors.current', true)
            ->orderBy('users.fname', 'asc')
            ->select('instructors.*')
            ->get();

        
        $groups = StaffGroup::where('slug', 'seniorstaff')->get();

        return view('about.staff', compact('leadership', 'web', 'events', 'groups', 'instructors'));
    }

    public function editIndex()
    {
        $staff = StaffMember::all();

        return view('admin.settings.staff', compact('staff'));
    }

    public function editStaffMember(Request $request, $id)
    {
        //Grab staff object
        $staff = StaffMember::whereId($id)->firstOrFail();

        //Check user given is a user
        $user = User::whereId($request->get('cid'))->first();
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'CID for staff member '.$staff->shortform.' invalid!');
        }

        //Ok we have a user.. assign them!
        $staff->user_id = $user->id;

        //Update description and email
        $staff->description = $request->get('description');
        $staff->email = $request->get('email');

        //Save it
        $staff->save();

        //Return!
        return redirect()->back()->with('success', 'Staff member '.$staff->shortform.' saved!');
    }
}
