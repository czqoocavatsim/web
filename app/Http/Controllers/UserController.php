<?php

namespace App\Http\Controllers;

use App\AuditLogEntry;
use App\Notifications\PermissionsChanged;
use App\UserNote;
use App\UserNotification;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Auth;
use Flash;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\User;

class UserController extends Controller
{
    public function privacyAccept()
    {
        $user = Auth::user();
        $user->init = 1;
        $user->save();
        return redirect('/dashboard')->with('success', 'Welcome to CZQO, '.$user->fname.'! We are glad to have you on board.');
    }

    public function viewAllUsers()
    {
        $users = User::all()->sortBy('id');
        return view('dashboard.users.list', compact('users'));
    }

    public function viewUserProfile($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $xml = [];
        //$xml['return'] = file_get_contents('https://cert.vatsim.net/cert/vatsimnet/idstatus.php?cid=' . $user->id);
        $xml['return'] = "sausage";
        $auditLog = AuditLogEntry::where('affected_id', $id)->get();
        return view('dashboard.users.profile', compact('user', 'xml', 'auditLog'));
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        if ($user->id == Auth::user()->id) {
            abort(403, 'You cannot delete yourself you fucking idiot.');
        }

        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => $user->id,
            'action' => 'DELETE USER',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0
        ]);
        $entry->save();
        $user->fname = "Deleted";
        $user->lname = "User";
        $user->email = "no-reply@czqo.vatcan.ca";
        $user->rating = "Deleted";
        $user->division = "Deleted";
        $user->permissions = 0;
        $user->deleted = 1;
        $user->save();
        return redirect()->route('users.viewall')->with('info', 'User deleted.');
    }

    public function editUser($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        return view('dashboard.users.edituser', compact('user'));
    }

    public function storeEditUser(Request $request, $id)
    {
        $user = User::find($id);
        $prevPermissions = $user->permissions;
        $user->permissions = $request->get('permissions');
        $user->save();
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'action' => 'EDIT USER',
            'affected_id' => $user->id,
            'time' => date('Y-m-d H:i:s'),
            'private' => 0
        ]);
        $entry->save();
        if ($prevPermissions != $user->permissions){
            $notification = new UserNotification([
                'user_id' => $user->id,
                'content' => 'Your permissions have been updated.',
                'link' => '/dashboard',
                'dateTime' => date('Y-m-d H:i:s')
            ]);
            $notification->save();
        }
        return redirect()->route('users.viewprofile', $user->id)->with('success', 'User edited!');
    }

    public function emailCreate($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('dashboard.users.email', compact('user'));
    }

    public function emailStore(Request $request)
    {

    }

    public function createUserNote(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        $user = User::where('id', $id)->firstOrFail();
        $note = new UserNote([
            'user_id' => $user->id,
            'author' => Auth::user()->id,
            'content' => $request->get('content'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        if ($request->get('confidential') == "on")
        {
            $note->confidential = 1;
        }

        $note->save();

        return redirect()->route('users.viewprofile', $user->id)->with('success', 'User note saved!');
    }

    public function deleteUserNote($user_id, $note_id)
    {
        $user = User::where('id', $user_id)->firstOrFail();
        $note = UserNote::where('id', $note_id)->where('user_id', $user->id)->firstOrFail();

        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'action' => 'DELETE USER NOTE '.$note->id,
            'affected_id' => $user->id,
            'time' => date('Y-m-d H:i:s'),
            'private' => 0
        ]);
        if ($note->confidential == 1)
        {
            $entry->private = 1;
        }
        $entry->save();

        $note->delete();

        return redirect()->route('users.viewprofile', $user->id)->with('success', 'User note deleted.');
    }

    public function changeAvatar(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        $user = Auth::user();
        $uploadedFile = $request->file('file');
        $filename = $uploadedFile->getClientOriginalName();
        Storage::disk('local')->putFileAs(
            'public/files/avatars/' . $user->id,
            $uploadedFile,
            $filename
        );
        $user->avatar = Storage::url('public/files/avatars/'.$user->id.'/'.$filename);
        $user->save();
        return redirect()->route('dashboard.index')->with('success', 'Avatar changed!');
    }

    public function searchUsers(Request $request)
    {
            $output = User::where('id', $request->get('search'))->get();
            return $output;
    }
}
