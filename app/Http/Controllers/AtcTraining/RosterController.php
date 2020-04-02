<?php

namespace App\Http\Controllers\AtcTraining;

use App\Http\Controllers\Controller;
use App\Models\Settings\AuditLogEntry;
use App\Models\AtcTraining\LegacyUser;
use App\Mail\RosterStatusMail;
use App\Models\AtcTraining\RosterMember;
use App\Models\Users\User;
use App\Models\Users\UserNotification;
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

}
