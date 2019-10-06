<?php

namespace App\Http\Controllers;

use App\AuditLogEntry;
use App\Instructor;
use App\StaffMember;
use App\User;
use Auth;
use Illuminate\Http\Request;

class StaffListController extends Controller
{
    public function index()
    {
        $staff = StaffMember::all();
        $instructors = Instructor::all();

        return view('staff', compact('staff', 'instructors'));
    }

    public function editIndex()
    {
        $staff = StaffMember::all();

        return view('dashboard.staff.index', compact('staff'));
    }

    public function editStaffMember(Request $request, $id)
    {
        //Grab staff object
        $staff = StaffMember::whereId($id)->firstOrFail();

        //Check user given is a user
        $user = User::whereId($request->get('cid'))->first();
        if (! $user) {
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
