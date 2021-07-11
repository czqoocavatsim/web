<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Models\Users\UserNotificationPreferences;
use App\Models\Users\UserPreferences;
use App\Models\Users\UserPrivacyPreferences;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class AuthController.
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/')->with('info', 'Logged out.');
    }

    /*
    Connect integration
    */
    public function connectLogin()
    {
        session()->forget('state');
        session()->forget('token');
        session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id'       => config('connect.client_id'),
            'redirect_uri'    => config('connect.redirect'),
            'response_type'   => 'code',
            'scope'           => 'full_name vatsim_details email',
            'required_scopes' => 'vatsim_details',
            'state'           => $state,
        ]);

        return redirect(config('connect.endpoint').'/oauth/authorize?'.$query);
    }

    public function validateConnectLogin(Request $request)
    {
        //Written by Harrison Scott
        $http = new Client();

        try {
            $response = $http->post(config('connect.endpoint').'/oauth/token', [
                'form_params' => [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => config('connect.client_id'),
                    'client_secret' => config('connect.secret'),
                    'redirect_uri'  => config('connect.redirect'),
                    'code'          => $request->code,
                ],
            ]);
        } catch (ClientException $e) {
            return redirect()->route('index')->with('error-modal', $e->getMessage());
        }
        session()->put('token', json_decode((string) $response->getBody(), true));

        try {
            $response = (new \GuzzleHttp\Client())->get(config('connect.endpoint').'/api/user', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '.session()->get('token.access_token'),
                ],
            ]);
        } catch (ClientException $e) {
            dd($e);
            return redirect()->route('index')->with('error-modal', $e->getMessage());
        }
        $response = json_decode($response->getBody());
        if (!isset($response->data->cid)) {
            return redirect()->route('index')->with('error-modal', 'There was an error processing data from Connect (No CID)');
        }
        if (!isset($response->data->vatsim->rating)) {
            return redirect()->route('index')->with('error-modal', 'We cannot create an account without VATSIM details.');
        }
        User::updateOrCreate(['id' => $response->data->cid], [
            'email'         => isset($response->data->personal->email) ? $response->data->personal->email : 'no-reply@ganderoceanic.ca',
            'fname'         => isset($response->data->personal->name_first) ? utf8_decode($response->data->personal->name_first) : $response->data->cid,
            'lname'         => isset($response->data->personal->name_last) ? $response->data->personal->name_last : $response->data->cid,
            'rating_id'     => $response->data->vatsim->rating->id,
            'rating_short'  => $response->data->vatsim->rating->short,
            'rating_long'   => $response->data->vatsim->rating->long,
            'rating_GRP'    => $response->data->vatsim->rating->long,
            'reg_date'      => null,
            'region_code'   => $response->data->vatsim->region->id,
            'region_name'   => $response->data->vatsim->region->name,
            'division_code' => $response->data->vatsim->division->id,
            'division_name' => $response->data->vatsim->division->name,
            'display_fname' => isset($response->data->personal->name_first) ? utf8_decode($response->data->personal->name_first) : $response->data->cid,
            'used_connect'  => true,
        ]);
        $user = User::find($response->data->cid);
        if (!isset($response->data->personal->name_first)) {
            $user->display_cid_only = true;
        }
        $user->save();
        Auth::login($user, true);
        if (!UserPreferences::where('user_id', $user->id)->first()) {
            $prefs = new UserPreferences();
            $prefs->user_id = $user->id;
            $prefs->ui_mode = 'light';
            $prefs->save();
        }
        if (!UserPrivacyPreferences::where('user_id', $user->id)->first()) {
            $priv = new UserPrivacyPreferences();
            $priv->user_id = $user->id;
            $priv->save();
        }
        if (!UserNotificationPreferences::where('user_id', $user->id)->first()) {
            $notif = new UserNotificationPreferences();
            $notif->user_id = $user->id;
            $notif->save();
        }

        return redirect()->route('my.index')->with('success', "Welcome back, {$user->fullName('F')}!");
    }
}
