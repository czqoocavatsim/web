<?php

namespace App\Mail;

use App\Models\AtcTraining\RosterMember;
use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RosterStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $controller;
    public $user;

    public function __construct(RosterMember $controller, User $user)
    {
        $this->controller = $controller;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.rosterstatus')
            ->subject('Your Roster Status Has Been Changed');
    }
}
