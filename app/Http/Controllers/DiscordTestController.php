<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordClient;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessRosterInactivity;
use App\Jobs\DiscordTrainingWeeklyUpdates;
use App\Jobs\ProcessShanwickControllers;

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
        $job = ProcessShanwickControllers::dispatch();

        // Call the handle method directly to get the result synchronously
        $result = $job->handle();

        return response()->json([
            'message' => 'Job executed successfully',
            'data' => $result,
        ]);
    }

    public function SendEmbed()
    {
     //New Applicant in Instructor Channel
     $discord = new DiscordClient();

     $discord->sendMessageWithEmbed('1274827382250934365', 'vatSys and Knowledgebase update!',
                                    
'We have some fantastic news regarding a few changes to Gander Oceanic over the past few days!

# vatSys Profile Completion
That vatSys NAT profile is in a public state ready for you to use! You can find a how to guide to get setup and started in the 

# Gander Oceanic Knowledgebase
We have made a conserted effort to overhaul the [Gander Oceanic Knowedgebase](https://knowledgebase.ganderoceanic.ca/controller/clients/vatSys/vatsysbasics/) after recognising that a large number of details where outdated. We have overhauled the Controller category, and are now beginning to review the the Pilot Procedures at this time.

### Controller SOPs are in Progress!
We plan to introduce all of our Operational Procedures into the Knowledgebase. This will allow for each of our controllers to find all of our operational requirements in an easy location.

If you are interested in helping out with this, please reach out to <@200426385863344129> and we can get you set up with assisting on the Knowledgebase.

Thank you all, and enjoy!
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

    public function sendMessage()
    {
        $discord = new DiscordClient();
        $discord->sendMessage('1274827382250934365', '<@&482819739996127259>');
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
