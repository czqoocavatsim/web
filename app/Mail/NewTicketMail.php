<?php

namespace App\Mail;

use App\Models\Tickets\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicketMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.newticket')
            ->subject('#'.$this->ticket->ticket_id.' | New Ticket Opened');
    }
}
