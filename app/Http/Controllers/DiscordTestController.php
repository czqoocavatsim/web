<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordClient;
use App\Jobs\DiscordAccountCheck;

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

    public function SendEmbed()
    {
     //New Applicant in Instructor Channel
     $discord = new DiscordClient();

     $discord->sendMessageWithEmbed('1274827382250934365', 'Discord Account Not Linked on CZQO',
                                    
'A number of users within the Discord do not have their Discord linked with the Gander Oceanic Web Service.

We ask that you head to the [Gander Oceanic Website](https://ganderoceanic.ca/my) to link your Discord Account.

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
}
