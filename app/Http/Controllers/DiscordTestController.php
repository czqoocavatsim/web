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
        $job = DiscordTrainingWeeklyUpdates::dispatch();

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

     $discord->sendMessageWithEmbed('488265136696459292', '',
                                    
'## Gander Oceanic - Submitting your feedback

We wanted to remind members that Gander Oceanic has functionality to **report issues / submit controller feedback** built native into the website.

Any of the following issues can be submitted to the Leadership Team for review and action:
- Controller Feedback
- Events / Marketing Feedback
- Operations Feedback
- Web Feedback
- General Feedback (All Other Queries)

This will be the easiest way moving forward for us to work on issues. Functionality will be implemented in the future where staff will update your feedback link with an action status, as well as the ability to request additional information as needed.

[Submit your feedback here](https://ganderoceanic.ca/my/feedback/new)

**Thank you all,
Gander Oceanic OCA**
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
