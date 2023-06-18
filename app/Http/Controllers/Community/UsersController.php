<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /*
    Users admin functions
    */

    public function index()
    {
        //Get all users
        $users = User::select(['id', 'fname', 'lname', 'rating_short', 'display_fname', 'display_cid_only', 'display_last_name'])->get();

        return view('admin.community.users.index', compact('users'));
    }

    public function viewUser($id)
    {
        //Get user
        $user = User::whereId($id)->firstOrFail();

        //Get assignable roles/perms
        $assignableRoles = Role::all();
        $assignablePermissions = Permission::all();

        return view('admin.community.users.view', compact('user', 'assignableRoles', 'assignablePermissions'));
    }

    public function assignUserRole(Request $request, $user_id)
    {
        //Validate it
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|int',
        ]);

        //No validate? Bad.
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        //Find the role and user
        $user = User::whereId($user_id)->firstOrFail();
        $role = Role::whereId($request->get('role_id'))->first();

        //No role? Bad.
        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        //User already has role? Bad.
        if ($user->hasRole($role)) {
            return back()->with('error', 'Role already assigned.');
        }

        //Is the role the "restricted" one and is this a staff member assigning it to themselves? -_-
        if ($role->name == 'Restricted' && $user->id == Auth::id()) {
            return back()->with('error', 'You cannot restrict yourself.');
        }

        //Okay! Assign them the role then.
        $user->assignRole($role);

        //Log it.
        activity()->causedBy(Auth::user())->performedOn($user)->log('Role \''.$role->name.'\' assigned');

        //Send good response back :)
        return back()->with('success', 'Role \''.$role->name.'\' assigned!');
    }

    public function removeUserRole(Request $request, $user_id)
    {
        //Validate it
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|int',
        ]);

        //No validate? Bad.
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        //Find the role and user
        $user = User::whereId($user_id)->firstOrFail();
        $role = Role::whereId($request->get('role_id'))->first();

        //No role? Bad.
        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        //User doesn't have role? Bad.
        if (!$user->hasRole($role)) {
            return back()->with('error', 'This user doesn\'t have that role.');
        }

        //Okay! Assign them the role then.
        $user->removeRole($role);

        //Log it.
        activity()->causedBy(Auth::user())->performedOn($user)->log('Role \''.$role->name.'\' removed');

        //Send good response back :)
        return back()->with('info', 'Role \''.$role->name.'\' removed.');
    }

    public function assignUserPermission(Request $request, $user_id)
    {
        //Validate it
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|int',
        ]);

        //No validate? Bad.
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        //Find the role and user
        $user = User::whereId($user_id)->firstOrFail();
        $permission = Permission::whereId($request->get('permission_id'))->first();

        //No permission? Bad.
        if (!$permission) {
            return back()->with('error', 'Permission not found.');
        }

        //User already has permission? Bad.
        if ($user->hasPermissionTo($permission)) {
            return back()->with('error', 'Permission already assigned.');
        }

        //Okay! Assign them the permission then.
        $user->givePermissionTo($permission);

        //Log it.
        activity()->causedBy(Auth::user())->performedOn($user)->log('Permission to \''.$permission->name.'\' given');

        //Send good response back :)
        return back()->with('success', 'Permission to \''.$permission->name.'\' given!');
    }

    public function removeUserPermission(Request $request, $user_id)
    {
        //Validate it
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|int',
        ]);

        //No validate? Bad.
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        //Find the role and user
        $user = User::whereId($user_id)->firstOrFail();
        $permission = Permission::whereId($request->get('permission_id'))->first();

        //No permission? Bad.
        if (!$permission) {
            return back()->with('error', 'Permission not found.');
        }

        //User doesn't have permission? Bad.
        if (!$user->hasPermissionTo($permission)) {
            return back()->with('error', 'This user doesn\'t have that permission.');
        }

        //Okay! Assign them the role then.
        $user->revokePermissionTo($permission);

        //Log it.
        activity()->causedBy(Auth::user())->performedOn($user)->log('Permission to \''.$permission->name.'\' revoked');

        //Send good response back :)
        return back()->with('info', 'Permission to \''.$permission->name.'\' revoked.');
    }

    public function resetUserBiography($user_id)
    {
        //Get user
        $user = User::whereId($user_id)->firstOrFail();

        //Reset their biography
        $user->bio = null;
        $user->save();

        //Log it
        activity()->causedBy(Auth::user())->performedOn($user)->log('Biography reset');

        //Return
        return redirect()->back()->with('info', 'User biography reset');
    }

    public function resetUserAvatar($user_id)
    {
        //Get user
        $user = User::whereId($user_id)->firstOrFail();

        //Reset their avatar
        $user->avatar = '';
        $user->avatar_mode = 0;
        $user->save();

        //Log it
        activity()->causedBy(Auth::user())->performedOn($user)->log('Avatar reset');

        //Return
        return redirect()->back()->with('info', 'User avatar reset');
    }
}
