<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vatsim\OAuth\SSO;
use Illuminate\Support\Str;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var SSO
     */
    private $sso;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->sso = new SSO(config('sso.base'), config('sso.key'), config('sso.secret'), config('sso.method'), config('sso.cert'), config('sso.additionalConfig'));
    }

    /**
     * Redirect user to VATSIM SSO for login.
     *
     * @throws \Vatsim\OAuth\SSOException
     */
    public function ssoLogin()
    {
        $this->sso->login(config('sso.return'), function ($key, $secret, $url) {
            session()->put('key', $key);
            session()->put('secret', $secret);
            session()->save();
            header('Location: '.$url);
            die();
        });
    }

    /**
     * Validate the login and access protected resources, create the user if they don't exist, update them if they do, and log them in.
     *
     * @param Request $get
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Vatsim\OAuth\SSOException
     */
    public $newUser;

    public function validateSsoLogin(Request $get)
    {
        $this->sso->validate(session('key'), session('secret'), $get->input('oauth_verifier'), function ($user, $request) {
            session()->forget('key');
            session()->forget('secret');
            User::updateOrCreate(['id' => $user->id], [
                'email' => $user->email,
                'fname' => utf8_decode($user->name_first),
                'lname' => $user->name_last,
                'rating_id' => $user->rating->id,
                'rating_short' => $user->rating->short,
                'rating_long' => $user->rating->long,
                'rating_GRP' => $user->rating->GRP,
                'reg_date' => $user->reg_date,
                'region_code' => $user->region->code,
                'region_name' => $user->region->name,
                'division_code' => $user->division->code,
                'division_name' => $user->division->name,
                'subdivision_code' => $user->subdivision->code,
                'subdivision_name' => $user->subdivision->name,
                'display_fname' => $user->name_first,
            ]);
            $user = User::find($user->id);
            Auth::login($user, true);
        });

        return redirect('/dashboard')->with('success', 'Logged in!');
    }

    /**
     * Check if the user was on the old roster, if so, certify them!
     */

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
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
            'client_id' => config('connect.client_id'),
            'redirect_uri' => config('connect.redirect'),
            'response_type' => 'code',
            'scope' => 'full_name vatsim_details email',
            'state' => $state,
        ]);

        return redirect("https://auth.vatsim.net/oauth/authorize?".$query);
    }

    public function validateConnectLogin(Request $request)
    {
        //Written by Harrison Scott
        $http = new Client;
        try {
        $response = $http->post('https://auth.vatsim.net/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => config('connect.client_id'),
                'client_secret' => config('connect.secret'),
                'redirect_uri' => config('connect.redirect'),
                'code' => $request->code,
            ],
        ]);
        } catch (ClientException $e){
            abort(500, $e->getResponse()->getBody());
        }
        session()->put('token', json_decode((string) $response->getBody(), true));
        try{
        $response = (new \GuzzleHttp\Client)->get('https://auth.vatsim.net/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.session()->get('token.access_token')
            ]
        ]);
        } catch(ClientException $e){
            abort(500, $e->getResponse()->getBody());
        }
        $response = json_decode($response->getBody());
        if(!isset($response->data->cid)){
        abort(500, 'No CID returned');
        }
        User::updateOrCreate(['id' => $response->data->cid], [
            'email' => $response->data->personal->email,
            'fname' => utf8_decode($response->data->personal->name_first),
            'lname' => $response->data->personal->name_last,
            'rating_id' => $response->data->vatsim->rating->id,
            'rating_short' => $response->data->vatsim->rating->short,
            'rating_long' => $response->data->vatsim->rating->long,
            'rating_GRP' => $response->data->vatsim->rating->long,
            'reg_date' => null,
            'region_code' => $response->data->vatsim->region->id,
            'region_name' => $response->data->vatsim->region->name,
            'division_code' => $response->data->vatsim->division->id,
            'division_name' => $response->data->vatsim->division->name,
            'display_fname' => utf8_decode($response->data->personal->name_first),
            'used_connect' => true
        ]);
        $user = User::find($response->data->cid);
        Auth::login($user, true);

        return redirect('/dashboard')->with('success', 'Logged in!');
    }
}
