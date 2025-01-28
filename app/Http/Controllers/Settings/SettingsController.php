<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\CoreSettings;
use App\Models\Settings\RotationImage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

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

    /*
    Emails
    */
    public function emails()
    {
        //Get settings
        $coreSettings = CoreSettings::find(1);

        //Return the view
        return view('admin.settings.emails', compact('coreSettings'));
    }

    /*
    Save emails
    */
    public function saveEmails(Request $request)
    {
        //Get the settings
        $coreSettings = CoreSettings::find(1);

        //Save changes
        $coreSettings->emailfirchief = $request->get('emailfirchief');
        $coreSettings->emaildepfirchief = $request->get('emaildepfirchief');
        $coreSettings->emailcinstructor = $request->get('emailcinstructor');
        $coreSettings->emaileventc = $request->get('emaileventc');
        $coreSettings->emailfacilitye = $request->get('emailfacilitye');
        $coreSettings->emailwebmaster = $request->get('emailwebmaster');
        $coreSettings->save();

        //Return the view
        return view('admin.settings.emails', compact('coreSettings'))->with('success', 'Emails saved');
    }

    /*
    Audit log
    */
    public function activityLog()
    {
        $entries = Activity::all();

        return view('admin.settings.activitylog', compact('entries'));
    }

    /*
    Rotation images
    */
    public function rotationImages()
    {
        $images = RotationImage::all()->sortByDesc('created_at');

        return view('admin.settings.rotationimages', compact('images'));
    }

    public function uploadRotationImage(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $basePath = 'assets/staff_uploads/rotation_images';
        $destinationPath = public_path($basePath);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);

        $image = new RotationImage();
        $image->path = "$basePath/$filename";
        $image->user_id = Auth::id();
        $image->save();

        return redirect()->back()->with('success', 'Image uploaded.');
    }

    public function deleteRotationImage($image_id)
    {

        // return $image_id;

        $image = RotationImage::whereId($image_id)->firstOrFail();

        $filePath = public_path($image->path);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return redirect()->back()->with('info', 'Image deleted.');
    }
}
