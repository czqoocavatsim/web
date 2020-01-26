<?php

namespace App\Mail\ActivityBot;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnauthorisedConnection extends Mailable
{
    use Queueable, SerializesModels;

    public $oc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($oc)
    {
        $this->oc = $oc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.unauthorisedconnection')
            ->subject('Unauthorised Connection Found');
    }
}
