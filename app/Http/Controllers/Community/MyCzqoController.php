<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Users\UserNotificationPreferences;
use App\Models\Users\UserPreferences;
use App\Models\Users\UserPrivacyPreferences;
use App\Notifications\WelcomeNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use mofodojodino\ProfanityFilter\Check;

class MyCzqoController extends Controller
{
    /*
    Privacy Policy/Account Init
    */
    public function acceptPrivacyPolicy(Request $request)
    {
        //Get the user
        $user = Auth::user();

        //If they're already initiated...
        if ($user->init) {
            //Return them back to myczqo
            return redirect()->route('my.index')->with('error', 'You have already accepted our privacy policy.');
        }

        //Initate them
        $user->init = 1;

        //Did they opt into emails?
        $notifPrefs = UserNotificationPreferences::where('user_id', $user->id)->first();

        if ($request->get('optInEmails') == 'on') {
            $notifPrefs->event_notifications = 'email';
            $notifPrefs->news_notifications = 'email';
            $notifPrefs->save();
        }

        //Save the user
        $user->save();

        //Send them the welcome email
        $user->notify(new WelcomeNewUser($user));

        //Redirect to myczqo
        return redirect()->route('my.index')->with('success', "Welcome to Gander Oceanic, {$user->fullName('F')}! We are glad to have you on board.");
    }

    public function denyPrivacyPolicy()
    {
        //Get the user
        $user = Auth::user();

        //If they're already initiated...
        if ($user->init) {
            //Return them back to myczqo
            return redirect()->route('my.index')->with('error-modal', 'To cease accepting our privacy policy and end your membership with Gander Oceanic, please contact us as specified in the Privacy Policy.');
        }

        //Well lets log them out
        Auth::logout($user);

        //Delete their privacy, notifs, prefs
        $notifPrefs = UserNotificationPreferences::where('user_id', $user->id)->first();
        $notifPrefs->delete();
        $privPrefs = UserPrivacyPreferences::where('user_id', $user->id)->first();
        $privPrefs->delete();
        $prefs = UserPreferences::where('user_id', $user->id)->first();
        $prefs->delete();

        //Delete the user
        $user->delete();

        //Redirect
        return redirect()->route('index')->with('info', 'Your account has been removed from Gander Oceanic as you did not accept our Privacy Policy.');
    }

    /*
    Biography
    */
    public function saveBiography(Request $request)
    {
        $this->validate($request, [
            'bio' => 'sometimes|max:5000',
        ]);

        //Get user
        $user = Auth::user();

        //Get input
        $input = $request->get('bio');

        //No swear words.. give them the new bio
        $user->bio = $input;
        $user->save();

        //Redirect
        return redirect()->back()->with('success', 'Biography saved! Be sure to check your biography privacy settings in Manage Preferences.');
    }

    /*
    Avatars
    */

    //Change avatar (custom image)
    public function changeAvatarCustomImage(Request $request)
    {
        //Validate
        $messages = [
            'file.mimes' => 'The image must be either a JPEG, PNG, JPG, or GIF file.'
        ];

        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        //Get user
        $user = Auth::user();

        //Put it onto disk
        $path = Storage::disk('digitalocean')->put('user_uploads/'.$user->id.'/avatars', $request->file('file'), 'public');

        //Change the avatar url and mode
        $user->avatar = Storage::url($path);
        $user->avatar_mode = 1;
        $user->save();

        //Return
        return redirect()->route('my.index')->with('success', 'Avatar changed to a custom image!');
    }

    //Change avatar (Discord)
    public function changeAvatarDiscord()
    {
        //Get user
        $user = Auth::user();

        //They need Discord don't they
        if (!$user->hasDiscord()) {
            return redirect()->route('my.index')->with('error-modal', 'You must link your Discord account must.');
        }

        //Change avatar mode and save
        $user->avatar_mode = 2;
        $user->save();

        //Return
        return redirect()->route('my.index')->with('success', 'Avatar changed to your Discord avatar!');
    }

    //Change avatar initials
    public function changeAvatarInitials()
    {
        //Get user
        $user = Auth::user();

        //Change mode and save
        $user->avatar_mode = 0;
        $user->save();

        //Return
        return redirect()->route('my.index')->with('success', 'Avatar changed to your initials!');
    }

    /*
    Display name
    */
    public function changeDisplayName(Request $request)
    {
        //Validate
        $this->validate($request, [
            'display_fname' => 'required',
            'format' => 'required',
        ]);

        //Get user
        $user = Auth::user();

        //Run through profanity filter
        $check = new Check();
        if ($check->hasProfanity($request->get('display_fname'))) {
            return redirect()->back()->withInput()->with('error', 'Profanity was detected in your display name. Please remove it');
        }

        //No swear words... give them the new name!
        $user->display_fname = $request->get('display_fname');
        if ($request->get('format') == 'showall') {
            $user->display_last_name = true;
            $user->display_cid_only = false;
        } elseif ($request->get('format') == 'showfirstcid') {
            $user->display_last_name = false;
            $user->display_cid_only = false;
        } else {
            $user->display_last_name = false;
            $user->display_cid_only = true;
        }
        $user->save();

        //Redirect
        return redirect()->back()->with('success', 'Display name saved! If your avatar is set to default, it may take a while for the initials to update.');
    }

    /*
    Preferences
    */
    public function preferences()
    {
        //Get preferences
        $preferences = Auth::user()->preferences;

        //return
        return view('my.preferences', compact('preferences'));
    }

    public function preferencesPost(Request $request)
    {
        //Validate
        $validator = Validator::make($request->all(), [
            'preference_name' => 'required',
            'value' => 'required',
            'table' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        //Get user's preferences object
        switch ($request->get('table')) {
            case 'main':
                $preferences = Auth::user()->preferences;
            break;
            case 'notifications':
                $preferences = UserNotificationPreferences::where('user_id', Auth::id())->first();
                break;
            case 'privacy':
                $preferences = UserPrivacyPreferences::where('user_id', Auth::id())->first();
        }

        //Change variable
        $preferences->{$request->get('preference_name')} = $request->get('value');
        $preferences->save();

        //Return
        return response()->json(['message' => 'Saved'], 200);
    }
}
