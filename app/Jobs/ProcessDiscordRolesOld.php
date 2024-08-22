<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessDiscordRolesOld implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $backoff = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $addRole, $discordId, $roleId, $token, $guildId;
    public function __construct($addRole, $discordId, $roleId)
    {
        $this->guildId = Config::get('services.discord.guild_id');
        $this->token = Config::get('services.discord.token');
        $this->discordId = $discordId;
        $this->addRole = $addRole;
        $this->roleId = $roleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);

        try {
            $client = new Client();
            $client->request($this->addRole ? 'PUT' : 'DELETE', "https://discord.com/api/v10/guilds/" . $this->guildId . "/members/" . $this->discordId . "/roles/" . $this->roleId, [
                'headers' => [
                    'Authorization' => 'Bot ' . $this->token,
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
}
