<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Users\StaffGroup;
use App\Models\Users\StaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    public function index()
    {
        //Get all staff members
        $staff = StaffMember::all();

        //Get all groups
        $groups = StaffGroup::all();

        //Return view
        return view('admin.settings.staff', compact('staff', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('addStaffMemberErrors', [
            'position' => 'required',
            'cid' => 'required|exists:users,id',
            'description' => 'nullable',
            'email' => 'required|email',
            'group_id' => 'required|exists:staff_groups,id'
        ], [
            'position.required' => 'Position name required',
            'cid.required' => 'CID required',
            'cid.exists' => 'CID must be a user',
            'email.required' => 'Email required',
            'email.email' => 'Email must be an email',
            'group_id.required' => 'Group required',
            'group_id.exists' => 'Group must be a group'
        ]);

        $staffMember = StaffMember::create([
            'user_id' => $request->cid,
            'email' => $request->email,
            'position' => $request->position,
            'group_id' => $request->get('group_id'),
            'group' => StaffGroup::find($request->group_id)->slug,
            'shortform' => strtolower(str_replace(' ', '', $request->position)),
            'description' => $request->description ?? 'Not provided'
        ]);

        Session::flash('info', 'Staff member created');
        return redirect()->route('settings.staff');
    }

    public function update(StaffMember $staffMember, Request $request)
    {
        $request->validateWithBag('editStaffMemberErrors', [
            'position' => 'required',
            'cid' => 'required|exists:users,id',
            'description' => 'nullable',
            'email' => 'required|email',
        ], [
            'position.required' => 'Position name required',
            'cid.required' => 'CID required',
            'cid.exists' => 'CID must be a user',
            'email.required' => 'Email required',
            'email.email' => 'Email must be an email',
        ]);

        $staffMember->update([
            'user_id' => $request->cid,
            'email' => $request->email,
            'position' => $request->position,
            'description' => $request->description ?? 'Not provided'
        ]);
        $staffMember->save();

        Session::flash('info', 'Staff member edited');
        return redirect()->route('settings.staff');
    }

    public function delete(StaffMember $staffMember)
    {
        $staffMember->delete();

        Session::flash('info', 'Staff member deleted');
        return redirect()->route('settings.staff');
    }
}
