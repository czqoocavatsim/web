<?php

namespace App\Http\Controllers;
include __DIR__.'/vendor/autoload.php';

use Illuminate\Http\Request;
use Auth;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
use RestCord\DiscordClient;
use App\DiscordRole;

class DiscordController extends Controller
{
    public function senddiscord() {
        session_start();
        $provider = new \Wohali\OAuth2\Client\Provider\Discord([
            'clientId' => '482857020496805898',
            'clientSecret' => 'KSUV1lrXe28hr_4e7HrtNliUQ9WNmb6q',
            'redirectUri' => 'http://localhost:8000/discord/process',
        ]);

        $options = [
            'scope' => ['identify'],
        ];

        if (!isset($_GET['code'])) {

        // Step 1. Get authorization code
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        header('Location: ' . $authUrl);
        exit();
        } else {
        }
    }

    public function process() {
        $provider = new \Wohali\OAuth2\Client\Provider\Discord([
            'clientId' => '482857020496805898',
            'clientSecret' => 'KSUV1lrXe28hr_4e7HrtNliUQ9WNmb6q',
            'redirectUri' => 'http://localhost:8000/discord/process',
        ]);
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        try {

            $user = $provider->getResourceOwner($token);

            // echo '<h2>Resource owner details:</h2>';
            // printf('Hello %s#%s!<br/><br/>', $user->getUsername(), $user->getDiscriminator());
            $discordUsername = $user->getId();
            setcookie('discordID', $discordUsername, time() + (86400 * 30));
            setcookie('discordusername', $user->getUsername(), time() + (86400 * 30));

        } catch (Exception $e) {
            // Failed to get user details
            exit('Failed to user info');
        }

      session_start();
      $_SESSION['discord_id'] = $discordUsername;
      return redirect('/discord/assignperms');
    }

    public function assignperms() {
        session_start();
        $guildid = 479250337048297483;
        $myid = $_SESSION['discord_id'];

        $roles = [
           "Guest" => 482835389640343562,
           "Trainee" => 482824058141016075,
           "CZQOCertified" => 482819739996127259
        ];


        $discord = new DiscordClient(['token' => 'NDgyODU3MDIwNDk2ODA1ODk4.DplAOA.jbdgUJu-P8_KT1FmeHFH4WQhFCA']); // Token is required

        $userid = (int)$_SESSION['discord_id'];

        $args = [
            'guild.id' => $guildid,
            'user.id' => $userid
        ];

        $discord->guild->addGuildMember($args);
        $discord->guild->modifyGuildMember(['guild.id' => $guildid, 'user.id' => $userid, 'nick' => Auth::user()->fname + Auth::user()->lname + Auth::user()->id]);
        return view('sausages');
    }
}
