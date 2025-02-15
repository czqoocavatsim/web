<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedbackSubmission;

class TicketController extends Controller
{
    // Admin View All Tickets
    public function adminViewAllTickets()
    {
        $open_tickets = FeedbackSubmission::whereIn('status', [0,1])->get();
        $closed_tickets = FeedbackSubmission::where('status', 2)->get();

        return view('admin.community.tickets.view-all', compact('open_tickets', 'closed_tickets'));
    }

    // Admin View Specific Ticket
    public function adminViewTicket($id)
    {
        $ticket = FeedbackSubmission::find($id);
        
        return view('admin.community.tickets.view', compact('ticket'));
    }

    // Admin Send Comment
    public function adminAddTicketComment()
    {

    }

    // Admin Edit Comment
    public function adminEditTicketComment()
    {

    }

    // Admin Update Status
    public function adminEditTicketStatus()
    {

    }
}
