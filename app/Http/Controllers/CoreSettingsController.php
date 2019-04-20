<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoreSettings;
use App\User;
use App\AuditLogEntry;
use App\Notifications\MaintenanceNotification;
use Auth;
use Artisan;

class CoreSettingsController extends Controller
{
    public function index()
    {
        $settings = CoreSettings::where('id', 1)->firstOrFail();
        return view('dashboard.coresettings', compact('settings'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sys_name' => 'required',
            'release' => 'required',
            'sys_build' => 'required',
            'copyright_year' => 'required',
            'emailfirchief' => 'required|email',
            'emaildepfirchief' => 'required|email',
            'emailcinstructor' => 'required|email',
            'emaileventc' => 'required|email',
            'emailfacilitye' => 'required|email',
            'emailwebmaster' => 'required|email',
        ]);
        $settings = CoreSettings::where('id', 1)->firstOrFail();
        $settings->sys_name = $request->get('sys_name');
        $settings->release = $request->get('release');
        $settings->sys_build = $request->get('sys_build');
        $settings->copyright_year = $request->get('copyright_year');
        $settings->emailfirchief = $request->get('emailfirchief');
        $settings->emaildepfirchief = $request->get('emaildepfirchief');
        $settings->emailcinstructor = $request->get('emailcinstructor');
        $settings->emaileventc = $request->get('emaileventc');
        $settings->emailfacilitye = $request->get('email_acilitye');
        $settings->emailwebmaster = $request->get('emailwebmaster');
        $settings->save();
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'CHANGED CORE SETTINGS',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0
        ]);
        $entry->save();
        return redirect()->route('coresettings')->with('success', 'Saved settings');
    }

    public function enableMaintenance()
    {
        abort(403);
        $users = User::where('permissions', 4)->get();
        \Notification::send($users, new MaintenanceNotification);
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'ENTER MAINTENANCE MODE',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0
        ]);
        $entry->save();
        Artisan::call('down', ['--message' => 'The CZQO website is down for maintenance. If you require assistance, please contact us at info@czqo.vatcan.ca', '--retry' => 30]);
        return redirect()->route('coresettings')->with('success', 'Maintenance mode enabled.');
    }
}
