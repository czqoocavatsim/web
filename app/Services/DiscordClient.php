<?php

namespace App\Services;

use GuzzleHttp\Client;
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

    public function assignRole($userId, $roleId)
    {
        if ($userId) {
            $response = $this->client->put('guilds/' . env('DISCORD_GUILD_ID') . "/members/{$userId}/roles/{$roleId}");

            return $response->getStatusCode() == 204;
        }

        return;
    }

    public function removeRole($userId, $roleId)
    {
        if ($userId) {
            $response = $this->client->delete('guilds/' . env('DISCORD_GUILD_ID') . "/members/{$userId}/roles/{$roleId}");

            return $response->getStatusCode() == 204;
        }

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
}