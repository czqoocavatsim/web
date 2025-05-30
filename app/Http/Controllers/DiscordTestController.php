<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordClient;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessRosterInactivity;
use App\Jobs\DiscordTrainingWeeklyUpdates;
use App\Jobs\ProcessShanwickControllers;
use App\Jobs\ProcessSessionLogging;
use App\Jobs\MassUserUpdates;
use App\Jobs\DiscordAccountCheck;

use App\Models\Users\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Roster\QuarterBeforeRemoval;
use App\Notifications\Roster\TwoMonthFromRemoval;
use App\Notifications\Roster\OneMonthFromRemoval;
use App\Notifications\Roster\SevenDaysFromRemoval;

class DiscordTestController extends Controller
{
    public function ThreadTest()
    {
        //New Applicant in Instructor Channel
        $discord = new DiscordClient();
        $discord->createTrainingThread('1273228164255977492', 'Joshua Micallef 1342084', '<@200426385863344129>');
    }

    public function EditTagTest()
    {
        $discord = new DiscordClient();
        $results = $discord->AddThreadTag('Test', 'Roster Placeholder 2');

        return $results;
    }

    public function Job()
    {
        // Dispatch the job
        $job = ProcessSessionLogging::dispatch();

        // Call the handle method directly to get the result synchronously
        $result = $job->handle();

        return response()->json([
            'message' => 'Job executed successfully',
            'data' => $result,
        ]);
    }

    public function Job2()
    {
        // Dispatch the job
        $job = MassUserUpdates::dispatch();

        // Call the handle method directly to get the result synchronously
        $result = $job->handle();

        return response()->json([
            'message' => 'Job executed successfully',
            'data' => $result,
        ]);
    }

    public function email()
    {
        $user = User::find('1342084');

        $currency = 0.55;

        Notification::send($user, new SevenDaysFromRemoval($user, $currency));
    }

    public function addReaction()
    {
        $discord = new DiscordClient();

        $discord->addReaction('🛠️');
    }

    public function getReactions()
    {
        $discord = new DiscordClient();

        $data = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('🛰️'));

        dd($data);
    }

    public function SendEmbed()
    {
     //New Applicant in Instructor Channel
     $discord = new DiscordClient();

     $discord->sendMessageWithEmbed('1347194167725522985', '',
                                    
'## Discord Notifications - Gander Oceanic
Welcome to the Gander Oceanic Discord,

In order to reduce pings recieved by our controllers. Opt-In Notifications are being created for our member base. This will allow you to select only the notifications which you are interested in.

:loudspeaker: - News Announcements
:calendar: - Event Announcement
:regional_indicator_c: - Cross the Pond Announcements
:satellite_orbital: - Controller Announcements
:airplane: - Pilot Announcements
:tools: - Tech Announcements

In order to have these roles assigned, please react to this message. To remove the role, please remove your reaction.

These roles will be updated hourly.

**Regards,**
***Gander Oceanic Staff Team***
');   

    }

    public function EditEmbed()
    {
     //New Applicant in Instructor Channel
     $discord = new DiscordClient();

     $discord->editMessageWithEmbed('1347194167725522985', '1347464850254725131', '',
                                    
'## Discord Notifications - Gander Oceanic
Welcome to the Gander Oceanic Discord,

In order to reduce pings recieved by our controllers. Opt-In Notifications are being created for our member base. This will allow you to select only the notifications which you are interested in.

:loudspeaker: - News Announcements
:calendar: - Event Announcement
:regional_indicator_c: - Cross the Pond Announcements
:satellite_orbital: - Controller Announcements
:airplane: - Pilot Announcements
:tools: - Tech Announcements

In order to have these roles assigned, please react to this message. To remove the role, please remove your reaction.

These roles will be updated hourly when the general discord role updates are conducted.

**Regards,**
***Gander Oceanic Staff Team***
');   

    }

    public function sendMessage()
    {
        $discord = new DiscordClient();
        $discord->sendMessage('1347194167725522985', 
'## Discord Notifications - Gander Oceanic
Welcome to the Gander Oceanic Discord,

In order to reduce pings recieved by our controllers. Opt-In Notifications are being created for our member base. This will allow you to select only the notifications which you are interested in.

- :megaphone: - News Announcements
- :calendar: - Event Announcement
- :regional_indicator_c: - Cross the Pond Announcements
- :satellite_orbital: - Operation Announcements
- :airplane: - Pilot Information

In order to have these roles assigned, please react to this message. To remove the role, please remove your reaction.

These roles will be updated hourly.

**Regards,**
***Gander Oceanic Staff Team***
');
    }

    public function DiscordRoles()
    {
            $discord = new DiscordClient();

            //Get role ID based off status
            $roles = [
                'certified' => 482819739996127259,
                'student' => 482824058141016075,
            ];

            $discord->removeRole(200426385863344129, $roles['student']);

            dd($discord);
    }

    public function SlashCommand()
    {
        $discord = new DiscordClient();

        $response = $discord->getClient()->post("applications/".env('DISCORD_CLIENT_ID')."/guilds/".env('DISCORD_GUILD_ID')."/commands", [
            'json' => [
                'name' => 'report-issue',
                'description' => 'Report a Web Issue',
                'type' => 1, // 1 for slash commands
            ]
        ]);
        
        return $response->json();
    }
}
