<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Jobs\ProcessDiscordRoles;
use GuzzleHttp\Exception\ClientException;

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
    public function createTrainingThread($channelId, $name, $user)
{
    try {
        $response = $this->client->post("channels/{$channelId}/threads", [
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

}

