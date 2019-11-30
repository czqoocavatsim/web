<?php

namespace App\Http\Controllers;

use App\AuditLogEntry;
use App\CoreSettings;
use App\Models\Settings\MaintenanceIPExemption;
use App\Notifications\MaintenanceNotification;
use App\User;
use Artisan;
use Auth;
use Illuminate\Http\Request;

class CoreSettingsController extends Controller
{
    public function index()
    {
        $settings = CoreSettings::where('id', 1)->firstOrFail();
        $ips = MaintenanceIPExemption::all();

        return view('dashboard.coresettings', compact('settings', 'ips'));
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
        $settings->emailfacilitye = $request->get('emailfacilitye');
        $settings->emailwebmaster = $request->get('emailwebmaster');
        $settings->save();
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'CHANGED CORE SETTINGS',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
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
            'private' => 0,
        ]);
        $entry->save();
        Artisan::call('down', ['--message' => 'The CZQO website is down for maintenance. If you require assistance, please contact us at info@czqo.vatcan.ca', '--retry' => 30]);

        return redirect()->route('coresettings')->with('success', 'Maintenance mode enabled.');
    }

    public function addExemptIp(Request $request)
    {
        $this->validate($request, [
            'label' => 'required|max:15',
            'ipv4' => 'required|unique:maintenance_i_p_exemptions|ipv4'
        ]);

        $ip = new MaintenanceIPExemption([
            'label' => $request->get('label'),
            'ipv4' => $request->get('ipv4')
        ]);

        $ip->save();

        AuditLogEntry::insert(Auth::user(), 'Added '. $ip->ipv4 . ' as exempt from maintenance mode', User::whereId(1)->first(), 1);

        return redirect()->back()->with('success', 'Added '. $ip->ipv4 .' as exempt!');
    }

    public function deleteExemptIp($id)
    {
        $ip = MaintenanceIPExemption::whereId($id)->firstOrFail();
        $ip->delete();
        return redirect()->back()->with('info', 'IP deleted.');
    }
}
