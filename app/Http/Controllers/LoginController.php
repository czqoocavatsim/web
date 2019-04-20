<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vatsim\OAuth\SSO;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;

/**
 * Class LoginController
 * @package App\Http\Controllers
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
     * Redirect user to VATSIM SSO for login
     *
     * @throws \Vatsim\OAuth\SSOException
     */
    public function login()
    {
        $this->sso->login(config('sso.return'), function ($key, $secret, $url) {
            session()->put('key', $key);
            session()->put('secret', $secret);
            session()->save();
            header('Location: ' . $url);
            die();
        });
    }


    /**
     * Validate the login and access protected resources, create the user if they don't exist, update them if they do, and log them in
     *
     * @param Request $get
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Vatsim\OAuth\SSOException
     */

    public $newUser;
    public function validateLogin(Request $get)
    {
        $this->sso->validate(session('key'), session('secret'), $get->input('oauth_verifier'), function ($user, $request) {
            session()->forget('key');
            session()->forget('secret');
            User::updateOrCreate(['id' => $user->id], ['email' => $user->email, 'fname' => $user->name_first, 'lname' => $user->name_last, 'rating' => $user->rating->short, 'division' => $user->division->code]);
            $user = User::find($user->id);
            Auth::login($user, true);
        });
        return redirect('/');
    }

    /**
     * Check if the user was on the old roster, if so, certify them!
     */

    /**
     * Log the user out
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
