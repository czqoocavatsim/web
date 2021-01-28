<?php

namespace App\Jobs;

use App\Models\Users\User;
use App\Notifications\DataExportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDataExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        $user = User::whereId($this->user->id)->with(['applications', 'staffProfile', 'rosterProfile', 'instructorProfile', 'studentProfile'])->firstOrFail();
        $userArray = $user->toArray();
        $discord = null;
        if ($user->hasDiscord()) {
            $discord = $user->getDiscordUser();
        }
        array_push($userArray, $discord);
        $json = json_encode($userArray, JSON_PRETTY_PRINT);
        $user->notify(new DataExportRequest($user, $json));
    }
}
