<?php

namespace App\Mail;

use App\TicketReply;
use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ticketReply;
    public $ticket;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TicketReply $ticketReply, Ticket $ticket)
    {
        $this->ticketReply = $ticketReply;
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticketreply')
            ->subject('#'.$this->ticket->ticket_id.' | New Reply from '.User::find($this->ticketReply->user_id)->fname.' '.User::find($this->ticketReply->user_id)->lname);
    }
}
