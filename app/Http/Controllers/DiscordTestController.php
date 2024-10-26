<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordClient;
use Illuminate\Support\Facades\Http;
use App\Jobs\DiscordTrainingWeeklyUpdates;
use App\Jobs\ProcessShanwickController;

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
        $job = ProcessShanwickController::dispatch();

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

     $discord->sendMessageWithEmbed(env('DISCORD_ANNOUNCEMENTS'), 'NEWS: Gander Oceanic Training - Open for C1+',
                                    
'We have an exciting opportunity for C1 controllers to join and be trained as oceanic controllers with Gander Oceanic. Our division boasts a dedicated and knowledgeable team of instructors who are committed to investing time into our students to help them attain oceanic controller status.

[Read this article](<https://ganderoceanic.ca/news/gander-oceanic-training-open-for-c1-2024-10-24>) to find out more, and details of how to apply!

**Thanks all,
Gander Oceanic Training Department**
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
        $discord->sendMessage('1298229107334844518', '<@&752767906768748586>');
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
