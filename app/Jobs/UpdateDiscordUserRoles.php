<?php

namespace App\Jobs;

use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RestCord\DiscordClient;

class UpdateDiscordUserRoles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Get Discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Counter of how many people were updated
        $counter = 0;

        //Get all Discord linked users
        foreach (User::where('discord_user_id', '!=', null)->cursor() as $user)
        {
            //Test if they're on the server or a staff member
            if (!$user->memberOfCzqoGuild() || $user->staffProfile || $user->isBot()) {
                //They're not.. continue on.
                continue;
            }



            //Get their current user, so we can compare changes
            $guildMember = $discord->guild->getGuildMember([
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $user->discord_user_id
            ]);

            //let's find all the roles they could possibly have...
            $rolesToAdd = array();

            //Here are all the role ids
            $discordRoleIds = array(
                'guest' => 482835389640343562,
                'training' => 482824058141016075,
                'certified' => 482819739996127259,
                'supervisor' => 720502070683369563
            );

            //Roster?
            if (!$user->rosterProfile) {
                //Not on the roster, we can give them guest.
                array_push($rolesToAdd, $discordRoleIds['guest']);
            } else {
                //What status do they have?
                $rosterProfile = $user->rosterProfile;
                switch ($rosterProfile->certification) {
                    case 'certified':
                        array_push($rolesToAdd, $discordRoleIds['certified']);
                        break;
                    case 'training':
                        array_push($rolesToAdd, $discordRoleIds['training']);
                        break;
                    default:
                        //Otherwise...
                        array_push($rolesToAdd, $discordRoleIds['guest']);
                }
            }

            //Supervisor?
            if ($user->rating_short == 'SUP') {
                array_push($rolesToAdd, $discordRoleIds['supervisor']);
            }

            //Create the full arguments
            $arguments = array(
                'guild.id' => intval(config('services.discord.guild_id')),
                'user.id' => $user->discord_user_id,
                'nick' => $user->fullName('FLC'),
                'roles' => $rolesToAdd
            );

            //Modify
            $discord->guild->modifyGuildMember($arguments);

            /* //Notify them if roles/nickname were change
            if ($user->fullName('FLC') != $guildMember->nick) {
                $discord->channel->createMessage([
                    'channel.id' => intval($user->discord_dm_channel_id),
                    'content' => 'Hi there! Your nickname on the Gander Oceanic Discord has been updated from "' . $guildMember->nick . '" to "' . $user->fullName('FLC') . '" as per your website nickname settings. If this is a mistake, please contact the Web Team.'
                ]);
            }

            if ($rolesToAdd !== $guildMember->roles) {
                $discord->channel->createMessage([
                    'channel.id' => intval($user->discord_dm_channel_id),
                    'content' => 'Hi there! Your roles on the Gander Oceanic Discord have been updated in line with your roster status. If there is a mistake, please contact the Web Team.'
                ]);
            }
 */
            //Counter!
            $counter++;
        }

        //Tell the log chat
        $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => $counter . ' users were updated automatically.']);
    }
}
