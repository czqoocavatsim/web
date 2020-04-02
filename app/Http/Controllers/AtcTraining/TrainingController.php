<?php

namespace App\Http\Controllers\AtcTraining;

use App\Http\Controllers\Controller;
use App\Models\AtcTraining\Application;
use App\Models\Settings\AuditLogEntry;
use App\Models\Settings\CoreSettings;
use App\Models\AtcTraining\InstructingSession;
use App\Models\AtcTraining\Instructor;
use App\Mail\ApplicationAcceptedStaffEmail;
use App\Mail\ApplicationAcceptedUserEmail;
use App\Mail\ApplicationDeniedUserEmail;
use App\Mail\ApplicationStartedStaffEmail;
use App\Mail\ApplicationStartedUserEmail;
use App\Mail\ApplicationWithdrawnEmail;
use App\Models\AtcTraining\RosterMember;
use App\Models\AtcTraining\Student;
use App\Models\Users\User;
use App\Models\Users\UserNotification;
use Auth;
use Calendar;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Mail;

class TrainingController extends Controller
{

}
