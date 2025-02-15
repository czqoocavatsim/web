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
        $job = ProcessRosterInactivity::dispatch();

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
        $job = ProcessRosterInactivity::dispatch();

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

    public function SendEmbed()
    {
     //New Applicant in Instructor Channel
     $discord = new DiscordClient();

     $discord->sendMessageWithEmbed('1214345937871179777', '',
                                    
'## Gander Oceanic - Training Process

Hello <@&482824058141016075>, welcome to Gander Oceanic!

The following is here to assist you in attaining your Oceanic Endorsement

### 1. Preparing for the Exam
- A  Computer-Based Training (CBT) for OCA controlling can be found [here](https://vats.im/gandercbt).
- Review [ATC Resources](https://ganderoceanic.ca/atc/resources) and the [Gander Oceanic Controller Knowledgebase](https://knowledgebase.ganderoceanic.ca/controller/) to begin gaining an understanding of our Operations and Policies.
After reviewing the above, you will be required to take an exam consisting of 20 Questions in relation to Oceanic proceedings within Gander and Shanwick. This exam is open book, and the pass mark is 80%.

### 2. Taking the Exam
In order for this exam to be assigned, you must visit the [VATCAN Website](https://vatcan.ca/) and log into the website.
Once you do this, head to your training thread in <#1226234767138226338> and request the exam by tagging the <@&1214350179151650898>

### 3. Live Session
You will be required to undertake a 90-minute training session with a Gander Oceanic Instructor on the Bandbox NAT_FSS.

> **Note:** *The session is a familiarisation session to ensure that you understand all the aspects within oceanic controlling, Euroscope profile, Plug-ins, CPDLC and Nattrak website.*

Our Instructors are located around the world, and therefor within different timezones. Instructors will aim to find availability with each student as quickly as possible.

Our system will automatically request you to provide new availability each fortnight. We kindly ask you follow the format sent within the message, and provide your times in Zulu Format.

Please ensure that you have reviewed any recent ⁠announcements and have the latest controller pack available prior to your session. 

Good luck with your study!

**Regards,
*Gander Oceanic Training Team***
');   

    }

    public function sendMessage()
    {
        $discord = new DiscordClient();
        $discord->sendMessage('488265136696459292', '<@&482835389640343562>');
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
