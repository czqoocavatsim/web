<?php

namespace App\Http\Controllers\Community;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Services\DiscordClient;
use App\Models\Roster\RosterMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use App\Models\Community\Discord\DiscordBan;
use App\Notifications\Discord\DiscordWelcome;

class DiscordController extends Controller
{
    public function joinShortcut()
    {
        return redirect()->route('index', ['discord' => '1']);
    }

    public function createDiscordBan(Request $request)
    {
        //Define validator messages
        $messages = [
            'reason.required'     => 'Please provide a ban reason.',
            'start_time.required' => 'Please provide a ban start time',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'reason'     => 'required',
            'start_time' => 'required',
            'user_id'    => 'required',
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            dd($validator->errors());

            return redirect()->back()->withInput()->withErrors($validator, 'createDiscordBanErrors');
        }

        //Create discord client
        // $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Get the user object
        $user = User::whereId($request->get('user_id'))->first();

        //Create the ban
        $ban = new DiscordBan([
            'user_id'      => $user->id,
            'moderator_id' => Auth::id(),
            'reason'       => $request->get('reason'),
            'start_time'   => $request->get('start_time'),
            'end_time'     => $request->get('end_time'),
            'discord_id'   => $user->discord_user_id,
        ]);

        $ban->save();

        //Notify user via bot and email
        // $user->notify(new BanNotification($user, $ban), [DiscordChannel::class, 'mail']);
    }

    /*
    Discord connection/server join
    */
    public function linkRedirectDiscord()
    {
        $query = http_build_query([
            'client_id' => env('DISCORD_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/my/discord/link/callback',
            'response_type' => 'code',
            'scope' => 'identify',
        ]);

        return redirect('https://discord.com/oauth2/authorize?' . $query);
    }

    public function linkCallbackDiscord(Request $request)
    {
        //Get access token using returned code
        $http = new Client();

        try {
            $response = $http->post('https://discord.com/api/v10/oauth2/token', [
                'form_params' => [
                    'client_id' => env('DISCORD_CLIENT_ID'),
                    'client_secret' => env('DISCORD_CLIENT_SECRET'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                    'redirect_uri' => env('APP_URL') . '/my/discord/link/callback',
                    'scope' => 'identify'
                ],
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
            ]);
        } catch (ClientException $e) {
            return redirect()->route('my.index')->with('error-modal', $e->getMessage());
        }

        $access_token = json_decode($response->getBody(), true)['access_token'];

        //Get User Details from access token
        try {
            $response = (new Client())->get('https://discord.com/api/v10/users/@me', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$access_token}"
                ],
            ]);
        } catch (ClientException $e) {
            return redirect()->route('my.index')->with('error-modal', $e->getMessage());
        }

        $discord_user = json_decode($response->getBody(), true);

        //Duplicate?
        if (User::where('discord_user_id', $discord_user['id'])->exists()) {
            return redirect()->route('my.index')->with('error-modal', 'This Discord account has already been linked by another user.');
        }

        $user = auth()->user();

        //Edit user
        $user->discord_user_id = $discord_user['id'];
        $user->discord_username = $discord_user['username'];
        $user->member_of_czqo = true;
        $user->discord_avatar = $discord_user['avatar'] ? 'https://cdn.discordapp.com/avatars/'.$discord_user['id'].'/'.$discord_user['avatar'].'.png' : null;
        $user->save();

        return redirect()->route('my.index')->with('success', 'Linked with account '.$discord_user['username'].'!');
    }

    public function joinRedirectDiscord()
    {
        $query = http_build_query([
            'client_id' => env('DISCORD_CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . '/my/discord/server/join/callback',
            'response_type' => 'code',
            'scope' => 'identify guilds.join',
        ]);

        return redirect('https://discord.com/oauth2/authorize?' . $query);
    }

    public function joinCallbackDiscord(Request $request)
    {
        //Get the current user
        $user = auth()->user();

        //let's find all the roles they could possibly have...
        $rolesToAdd = [];

        //Here are all the role ids
        $discordRoleIds = [
            'guest'      => 482835389640343562,
            'training'   => 482824058141016075,
            'certified'  => 482819739996127259,
            'supervisor' => 720502070683369563,
        ];

        //Add the Member role
        array_push($rolesToAdd, $discordRoleIds['guest']);

        //Roster?
        if (RosterMember::where('user_id', $user->id)->exists()) {
            //What status do they have?
            $rosterProfile = RosterMember::where('user_id', $user->id)->first();
            switch ($rosterProfile->certification) {
                case 'certified':
                    array_push($rolesToAdd, $discordRoleIds['certified']);
                    break;
                case 'training':
                    array_push($rolesToAdd, $discordRoleIds['training']);
                    break;
            }
        }

        //Supervisor?
        if ($user->rating_short == 'SUP') {
            array_push($rolesToAdd, $discordRoleIds['supervisor']);
        }

        //Get access token using returned code
        $http = new Client();

        try {
            $response = $http->post('https://discord.com/api/v10/oauth2/token', [
                'form_params' => [
                    'client_id' => env('DISCORD_CLIENT_ID'),
                    'client_secret' => env('DISCORD_CLIENT_SECRET'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                    'redirect_uri' => env('APP_URL') . '/my/discord/server/join/callback',
                    'scope' => 'identify guilds.join'
                ],
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
            ]);
        } catch (ClientException $e) {
            return redirect()->route('my.index')->with('error-modal', $e->getMessage());
        }

        $access_token = json_decode($response->getBody(), true)['access_token'];

        //Make em join Discord 
        try {
            $response = (new Client())
                ->put(
                    'https://discord.com/api/v10/guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id,
                    [
                        'headers' => [
                            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN')
                        ],
                        'json' => [
                            'access_token' => $access_token,
                            'nick' => auth()->user()->fullName('FLC'),
                            'roles' => $rolesToAdd
                        ]
                    ]
                );
        } catch (ClientException $e) {
            return redirect()->route('my.index')->with('error-modal', $e->getMessage());
        }


        //DM them
        $user->notify(new DiscordWelcome());

        $user->member_of_czqo = true;
        $user->save();

        //Log it
        $discord = new DiscordClient();
        $discord->sendMessage(482860026831175690, '['.Carbon::now()->toDateTimeString().'] <@'.$user->discord_user_id.'> ('.auth()->id().') has joined the guild');

        //And back to the dashboard
        return redirect()->route('my.index')->with('success', 'You have joined the CZQO Discord server!');
    }

    public function unlinkDiscord()
    {
        //Get user
        $user = auth()->user();

        //If they're a member of the Discord
        if ($user->member_of_czqo) {
            $http = new Client();

            try {
                $http->delete('https://discord.com/api/v10/guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id, 
                    [
                        'headers' => ['Authorization' => 'Bot '.env('DISCORD_BOT_TOKEN')]
                    ]);
            } catch (ClientException $e) {
                return redirect()->route('my.index')->with('error-modal', $e->getMessage());
            }
        }

        //Remove details from DB
        $user->discord_user_id = null;
        $user->member_of_czqo = false;
        $user->discord_avatar = null;

        //If they have a Discord avatar, remove it
        if ($user->avatar_mode == 2) {
            $user->avatar_mode = 0;
        }

        //Save
        $user->save();

        //Redirect
        return redirect()->route('my.index')->with('info', 'Discord account unlinked.');
    }
}
