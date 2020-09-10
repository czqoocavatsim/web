<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\Discord\DiscordBan;
use App\Models\Users\User;
use App\Notifications\Discord\BanNotification;
use App\Notifications\DIscord\DiscordWelcome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordChannel;
use RestCord\DiscordClient;
use SocialiteProviders\Manager\Config;
use Throwable;

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
            'reason.required' => 'Please provide a ban reason.',
            'start_time.required' => 'Please provide a ban start time',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'start_time' => 'required',
            'user_id' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()->withInput()->withErrors($validator, 'createDiscordBanErrors');
        }

        //Create discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Get the user object
        $user = User::whereId($request->get('user_id'))->first();

        //Create the ban
        $ban = new DiscordBan([
            'user_id' => $user->id,
            'moderator_id' => Auth::id(),
            'reason' => $request->get('reason'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'discord_id' => $user->discord_user_id
        ]);

        $ban->save();

        //Notify user via bot and email
        $user->notify(new BanNotification($user, $ban), [DiscordChannel::class, 'mail']);

        dd($ban);
    }

    /*
    Discord connection/server join
    */
    public function linkRedirectDiscord($param = null)
    {
        //Create the config
        if ($param == 'server_join_process') {
            $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect_server_join_process'));
        } else {
            $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect'));
        }

        //Redirect to Discord OAuth
        return Socialite::with('discord')->setConfig($config)->setScopes(['identify'])->redirect();
    }

    public function linkCallbackDiscord($param = null)
    {
        //Get Discord account
        if ($param == 'server_join_process') {
            $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect_server_join_process'));
        } else {
            $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect'));
        }
        $discordAccount = Socialite::driver('discord')->setConfig($config)->user();

        //If it doesn't exist...
        if (!$discordAccount) {
            return redirect()->route('dashboard.index')->with('error-modal', 'There was an error linking your account. Contact the Web Team for assistance.');
        }

        //Get current user
        $user = Auth::user();

        //Duplicate?
        if (User::where('discord_user_id', $discordAccount->id)->first()) {
            return redirect()->route('dashboard.index')->with('error-modal', 'This Discord account has already been linked by another user.');
        }

        //Edit user
        $user->discord_user_id = $discordAccount->id;
        $user->discord_dm_channel_id = app(Discord::class)->getPrivateChannel($discordAccount->id);
        $user->save();

        //Redirect to dashboard or server join
        if ($param == 'server_join_process') {
            return redirect()->route('me.discord.join');
        } else {
            return redirect()->route('dashboard.index')->with('success', 'Linked with account '.$discordAccount->nickname. '!');
        }
    }

    public function unlinkDiscord()
    {
        //Create discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Get user
        $user = Auth::user();

        //If they're a member of the Discord, and not a staff member
        if ($user->memberOfCzqoGuild() && !$user->staffProfile) {
            //In case of an unauthorised response
            try {
                //Remove member
                $discord->guild->removeGuildMember(['guild.id' => 479250337048297483, 'user.id' => $user->discord_user_id]);
                //Log
                $discord->channel->createMessage([
                    'channel.id' => 482860026831175690,
                    'content' => '['. Carbon::now()->toDateTimeString() . '] <@'.$user->discord_user_id.'> ('.Auth::id().') unlinked account, removed from guild'
                ]);
            } catch (Throwable $ex) {
                Log::error($ex);
            }
        }

        //Remove details from DB
        $user->discord_user_id = null;
        $user->discord_dm_channel_id = null;

        //If they have a Discord avatar, remove it
        if ($user->avatar_mode == 2) {
            $user->avatar_mode = 0;
        }

        //Save
        $user->save();

        //Redirect
        return redirect()->route('dashboard.index')->with('info', 'Discord account unlinked.');
    }

    public function joinRedirectDiscord()
    {
        $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect_join'));
        return Socialite::with('discord')->setConfig($config)->setScopes(['identify', 'guilds.join'])->redirect();
    }

    public function joinCallbackDiscord()
    {
        //Create Discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Get Discord account data
        $config = new Config(config('services.discord.client_id'), config('services.discord.client_secret'), config('services.discord.redirect_join'));
        $discordAccount = Socialite::driver('discord')->setConfig($config)->user();

        //Get the current user
        $user = Auth::user();

        //let's find all the roles they could possibly have...
        $rolesToAdd = array();

        //Here are all the role ids
        $discordRoleIds = array(
            'guest' => 482835389640343562,
            'training' => 482824058141016075,
            'certified' => 482819739996127259,
            'supervisor' => 720502070683369563
        );

        //Add the Member role
        array_push($rolesToAdd, $discordRoleIds['guest']);

        /* //Roster?
        if ($user->rosterProfile) {
            //What status do they have?
            $rosterProfile = $user->rosterProfile;
            switch ($rosterProfile->status) {
                case 'certified':
                    array_push($rolesToAdd, $discordRoleIds['certified']);
                    break;
                case 'training':
                    array_push($rolesToAdd, $discordRoleIds['training']);
                    break;
            }
        } */

        //Supervisor?
        if ($user->rating_short == 'SUP') {
            array_push($rolesToAdd, $discordRoleIds['supervisor']);
        }

        //Create the full arguments
        $arguments = array(
            'guild.id' => intval(config('services.discord.guild_id')),
            'user.id' => $user->discord_user_id,
            'access_token' => $discordAccount->token,
            'nick' => $user->fullName('FLC'),
            'roles' => $rolesToAdd
        );

        //Add them to guild
        $discord->guild->addGuildMember($arguments);

        //DM them
        Auth::user()->notify(new DiscordWelcome());

        //Log it
        $discord->channel->createMessage([
            'channel.id' => 482860026831175690,
            'content' => '['. Carbon::now()->toDateTimeString() . '] <@'.$user->discord_user_id.'> ('.Auth::id().') has joined the guild'
        ]);

        //And back to the dashboard
        return redirect()->route('dashboard.index')->with('success', 'You have joined the CZQO Discord server!');
    }
}
