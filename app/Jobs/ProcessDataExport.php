<?php

namespace App\Jobs;

use App\Models\Users\User;
use App\Notifications\DataExportRequest;
use App\Notifications\WelcomeNewUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDataExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::whereId($this->user->id)->with(['notes', 'applications', 'instructorProfile', 'studentProfile', 'staffProfile', 'rosterProfile', 'notifications', 'bookingBanObj', 'discordBans', 'tickets', 'ticketReplies'])->firstOrFail();
        Log::info('Processing GDPR Export All for '.$this->user->id);
        $userArray = $user->toArray();
        $discord = null;
        if ($user->hasDiscord()) {
            $discord = $user->getDiscordUser();
        }
        array_push($userArray, $discord);
        $json = json_encode($userArray, JSON_PRETTY_PRINT);
        Log::info($json);
        $user->notify(new DataExportRequest($user, $json));
    }
}
