<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordClient;
use Illuminate\Support\Facades\Http;
use App\Jobs\DiscordAccountCheck;
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
        $job = DiscordAccountCheck::dispatch();

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

     $discord->sendMessageWithEmbed('1274827382250934365', 'Discord Account Not Linked on CZQO',
                                    
'Dear Gander Members,

If you are seeing this message, it means your discord account is not linked with our Gander Oceanic Website. As such, your access to the discord has been limited until you link your account.

We ask that you head to the [Gander Oceanic Website](https://ganderoceanic.ca/my) to link your Discord Account. Your roles will be assigned at 0400z each day.

This will assist with future upgrades to the Discord Infrastructure.

<@&1278606316906090527>');   
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
        $discord->sendDM('200426385863344129', 'Test message');
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
