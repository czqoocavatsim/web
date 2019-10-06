<?php

namespace App\Mail;

use App\RosterMember;
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

    public function __construct(RosterMember $controller)
    {
        $this->controller = $controller;
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
