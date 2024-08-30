<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Jobs\ProcessDiscordRoles;
use GuzzleHttp\Exception\ClientException;
use Carbon\Carbon;

class DiscordClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://discord.com/api/v10/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bot '.env('DISCORD_BOT_TOKEN'),
            ],
        ]);
    }

    // Get Discord Client (For external use outside of this php file)
    public function getClient() {
        return $this->client;
    }

    public function sendMessage($channelId, $message)
    {
        $response = $this->client->post("channels/{$channelId}/messages", [
            'json' => [
                "content" => $message
            ]
        ]);

        return $response->getStatusCode() == 200;
    }

    public function sendDM($userId, $title, $message)
    {
        try{

            $response = $this->client->post("https://discord.com/api/v10/users/@me/channels", [
                'json' => [
                    'recipient_id' => $userId, // Replace $userId with the Discord user ID
                ],
            ]);
            
            $channel = json_decode($response->getBody(), true);
            $channelId = $channel['id'];
            
            // Step 2: Send the Message to the DM Channel
            $this->sendMessageWithEmbed($channelId, $title, $message);

        } catch (GuzzleHttp\Exception\ClientException $e) {

        }
    }

    public function sendMessageWithEmbed($channelId, $title, $description)
    {
        $response = $this->client->post("channels/{$channelId}/messages", [
            'json' => [
                "tts" => false,
                "embeds" => [
                    [
                        'title' => $title,
                        'description' => $description,
                        'color' => hexdec('0080C9'),
                    ]
                ]
            ]
        ]);

        return $response->getStatusCode() == 200;
    }

    public function ControllerConnection($callsign, $name)
    {
        $response = $this->client->post("channels/1275443682992197692/messages", [
            'json' => [
                "tts" => false,
                "embeds" => [
                    [
                        'title' => $callsign.' is currently online!',
                        'description' => 'There is currently ATC being provided over the Ocean!

**Controller:** '.$name.'
**Online from:** <t:'.Carbon::now()->timestamp.':t>',
                        'color' => hexdec('6EC40C'),
                    ]
                ]
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        return $responseData['id'];
    }

    public function ControllerDisconnect($id, $callsign, $name, $connect_time, $total_time)
    {
        $response = $this->client->patch("channels/1275443682992197692/messages/{$id}", [
            'json' => [
                "tts" => false,
                "embeds" => [
                    [
                        'title' => $callsign.' is Offline',
                        'description' => $name.' was connected to '.$callsign.'.
                        
Online from <t:'.Carbon::parse($connect_time)->timestamp.':t> to <t:'.Carbon::now()->timestamp.':t>.
Time: '.sprintf('%d hours %d minutes', intdiv($total_time, 1), ($total_time - intdiv($total_time, 1)) * 60),

                        'color' => hexdec('990000'),
                    ]
                ]
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        return $responseData;
    }

    public function assignRole($discordId, $roleId)
    {
        sleep(1);

        try {
            $client = new Client();
            $client->request('PUT', "https://discord.com/api/v10/guilds/".env('DISCORD_GUILD_ID')."/members/" . $discordId . "/roles/" . $roleId, [
                'headers' => [
                    'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
                    'Content-Type' => 'application/json'
                ],
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
            return;
        }
    }

    public function removeRole($discordId, $roleId)
    {
        sleep(2);
        
        try {
            $client = new Client();
            $client->request('DELETE', "https://discord.com/api/v10/guilds/".env('DISCORD_GUILD_ID')."/members/" . $discordId . "/roles/" . $roleId, [
                'headers' => [
                    'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
                    'Content-Type' => 'application/json'
                ],
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }
            return;
        }
    }

    public function changeName($userId, $name)
    {
        if ($userId) {

            try {
                $this->client->patch('guilds/' . env('DISCORD_GUILD_ID') . "/members/{$userId}", [
                    'json' => [
                        'nick' => $name
                    ]
                ]);
            } catch (ClientException $e) {
                return;
            }

            return;
        }

    }

    // Function to create a user training thread
    public function createTrainingThread($name, $user)
{
    try {
        $response = $this->client->post("channels/".env('DISCORD_TRAINING_FORUM')."/threads", [
            'json' => [
                'name' => $name,
                'applied_tags' => [1271845980865695774], //Tag ID for 'New Request'
                'message' => [
                    'content' => $user.', your application has now been approved. Welcome to Gander Oceanic! 

Please review <#1214345937871179777> in order to get yourself up to speed with our training process. It is pretty easy, but there are a few steps you *must* do in order to begin your training.

Once you have done so, and you are ready to attempt the exam, please ping `@exam-request` to have the Oceanic Exam assigned. You will only have 48 Hours to complete this exam, so please make sure you are ready.

After you pass the exam, please provide 7-days of availability for our Instructors.

Good luck with your study!',
                ],
            ],

        ]);
        
    
        $responseData = json_decode($response->getBody(), true);
        // Process $responseData as needed
    } catch (\Exception $e) {
        // Handle exception
        echo 'Error: ' . $e->getMessage();
    }
     
}

    public function closeTrainingThread($cid, $discord_id, $status)
    {
        // Get active Discord Threads
        $active_threads = $this->client->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');

        // Decode Data
        $threads_data = json_decode($active_threads->getBody(), true);

        foreach ($threads_data['threads'] as $thread) {
            if (strpos($thread['name'], $cid) !== false) {

                if($status == "certify"){
                    $this->sendMessageWithEmbed($thread['id'], 'Oceanic Training Completed!',
'Congratulations <@'.$discord_id.'>, you have now been certified on Gander & Shanwick Oceanic!
                
This training thread will now be closed due to the completion of your training. Your discord roles will automatically be updated within the next 24

If you have any questions, please reach out to your Instructor, or ask your question in <#836707337829089322>.

Enjoy controlling Gander & Shanwick OCA!

**Kind Regards,
Gander Oceanic Training Team**');

                } elseif($status == "cancel") {
                    $this->sendMessageWithEmbed($thread['id'], 'Oceanic Training Cancelled',
'<@'.$discord_id.'>, Your training request with Gander Oceanic has been terminated at <t:'.Carbon::now()->timestamp.':F>

If you would like to begin training again, please re-apply via the Gander Website.

**Kind Regards,
Gander Oceanic Training Team**');

                } elseif($status == "terminate"){
                    $this->sendMessageWithEmbed($thread['id'], 'Oceanic Training Terminated',
'<@'.$discord_id.'>, Your training request with Gander Oceanic has been terminated at <t:'.Carbon::now()->timestamp.':F>. 
                    
This is due to not completing the Exam within 60 Days of your application being accepted.
                    
If you would like to begin training again, please re-apply via the Gander Website.

**Kind Regards,
Gander Oceanic Training Team**');
                }

                // Lock and Archive the Thread
                $data = $this->client->patch('channels/'.$thread['id'], [
                    'json' => [
                        'locked' => true,
                        'archived' => true,
                    ]
                ]);
            }
        }

        // foreach($threads as $thread){
        //     if($thread['name'] == $name){
        //         return "Success!";
        //     }
        // }
    }

    public function EditThreadTag($lable, $cid)
    {
        // Get Training Tags and Threads from Discord
        $tag_responses = $this->client->get('channels/'.intval(config('services.discord.training_forum')));
        $active_threads = $this->client->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');

        // Decode Data in JSON
        $tag_data = json_decode($tag_responses->getBody(), true);
        $threads_data = json_decode($active_threads->getBody(), true);

        // Filter Tag/Thread data into easier format
        $tag_details = [];
        $tags = $tag_data['available_tags'];

        $thread_details = [];
        $threads = $threads_data['threads'];

        // Loop through each Thread, and then through each tag to see if a match is found.
        foreach($threads as $thread){
            foreach($tags as $tag){
                if (strpos($thread['name'], $cid) !== false && $tag['name'] == $lable) {
                    // Update Tag with new details
                    $data = $this->client->patch('channels/'.$thread['id'], [
                        'json' => [
                            'applied_tags' => [$tag['id']],
                        ]
                    ]);

                    break;
                }
            }
        }
    }

    // Send Embed Message in Training Thread
    public function sendEmbedInTrainingThread($cid, $title, $message)
    {
        // Get active Discord Threads
        $active_threads = $this->client->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');

        // Decode Data
        $threads_data = json_decode($active_threads->getBody(), true);

        // Loop through all threads to find students training record
        foreach ($threads_data['threads'] as $thread) {
            if (strpos($thread['name'], $cid) !== false) {
                // Send Embed Message
                $this->sendMessageWithEmbed($thread['id'], $title, $message);
            }
        }
    }

    }

