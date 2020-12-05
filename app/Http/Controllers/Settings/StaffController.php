<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Users\StaffGroup;
use App\Models\Users\StaffMember;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function editStaff()
    {
        //Get all staff members
        $staff = StaffMember::all();

        //Get all groups
        $groups = StaffGroup::all();

        //Return view
        return view('admin.settings.staff', compact('staff', 'groups'));
    }
}
