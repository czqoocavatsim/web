<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\AuditLogEntry;
use App\Models\Settings\CoreSettings;
use App\Models\Settings\MaintenanceIPExemption;
use App\Notifications\MaintenanceNotification;
use App\Models\Users\User;
use Artisan;
use Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    /*
    Site info
    */
    public function siteInformation()
    {
        //Get the settings
        $coreSettings = CoreSettings::find(1);

        //Return the view
        return view('admin.settings.siteinformation', compact('coreSettings'));
    }

    /*
    Save site info
    */
    public function saveSiteInformation(Request $request)
    {
        //Get the settings
        $coreSettings = CoreSettings::find(1);

        //Save changes
        $coreSettings->sys_name = $request->get('sys_name');
        $coreSettings->release = $request->get('release');
        $coreSettings->sys_build = $request->get('sys_build');
        $coreSettings->copyright_year = $request->get('copyright_year');
        $coreSettings->save();

        //Return the view
        return view('admin.settings.siteinformation', compact('coreSettings'))->with('success', 'Settings saved');
    }
}
