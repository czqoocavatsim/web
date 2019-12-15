<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\AuditLogEntry;
use App\Models\Users\User;
use Auth;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $entries = AuditLogEntry::all();

        return view('dashboard.auditlog', compact('entries'));
    }

    public function insert(Request $request)
    {
        $validateddata = $request->validate([
            'message' => 'required',
            'affected_id' => 'required',
        ]);

        $affected_user = User::where('id', $request->get('affected_id'))->first();

        if ($affected_user === null) {
            return redirect()->route('auditlog')->withErrors(['affected_id' => 'Invalid User ID'])->withInput();
        }

        $private = 0;

        if ($request->get('private') == 'yes') {
            $private = 1;
        }

        AuditLogEntry::insert(Auth::user(), $request->get('message'), $affected_user, $private);

        return redirect()->route('auditlog')->with('success', 'Entry inserted!');
    }
}
