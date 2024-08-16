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

    public function sendMessage($channelId, $message)
    {
        $response = $this->client->post("channels/{$channelId}/messages", [
            'json' => [
                "content" => $message
            ]
        ]);

        return $response->getStatusCode() == 200;
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

    public function assignRole($discordId, $roleId)
    {
        ProcessDiscordRoles::dispatch(true, $discordId, $roleId);

        return;
    }

    public function removeRole($discordId, $roleId)
    {
        ProcessDiscordRoles::dispatch(false, $discordId, $roleId);

        return;
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

    public function closeTrainingThread($name, $status)
    {
        // Get active Discord Threads
        $active_threads = $this->client->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');

        // Decode Data
        $threads_data = json_decode($active_threads->getBody(), true);

        foreach ($threads_data['threads'] as $thread) {
            if ($thread['name'] == $name) {

                if($status == "certify"){
                    $this->sendMessageWithEmbed($thread['id'], 'Oceanic Training Completed!',
'Congratulations, you have now been certified on Gander & Shanwick Oceanic!
                
This training thread will now be closed due to the completion of your training.

If you have any questions, please reach out to your Instructor, or ask your question in <#836707337829089322>.

Enjoy controlling Gander & Shanwick OCA!');
                } elseif($status == "cancel") {
                    $this->sendMessageWithEmbed($thread['id'], 'Oceanic Training Cancelled',
'Your training request with Gander Oceanic has been terminated on <t:'.Carbon::now()->timestamp.':F>

If you would like to begin training again, please re-apply via the Gander Website.');
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

    public function EditThreadTag($lable, $name)
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
                if($tag['name'] == $lable && $thread['name'] == $name){
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
    public function sendEmbedInTrainingThread($name, $title, $message)
    {
        // Get active Discord Threads
        $active_threads = $this->client->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');

        // Decode Data
        $threads_data = json_decode($active_threads->getBody(), true);

        // Loop through all threads to find students training record
        foreach ($threads_data['threads'] as $thread) {
            if ($thread['name'] == $name) {
                // Send Embed Message
                $this->sendMessageWithEmbed($thread['id'], $title, $message);
            }
        }
    }

    }

