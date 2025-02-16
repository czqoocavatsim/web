<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedbackSubmission;
use App\Models\Feedback\FeedbackTypeFieldSubmission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TicketController extends Controller
{
    // Admin View All Tickets
    public function adminViewAllTickets()
    {
        if(auth()->user()->hasRole('Senior Staff')){
        } else {
            return back()->with('error', 'You have insufficient permissions to view tickets. If this is a mistake, please contact Joshua M');
        }

        $open_tickets = FeedbackSubmission::whereIn('status', [0,1])->get();
        $closed_tickets = FeedbackSubmission::where('status', 2)->get();

        return view('admin.community.tickets.view-all', compact('open_tickets', 'closed_tickets'));
    }

    // Admin View Specific Ticket
    public function adminViewTicket($id)
    {
        if(auth()->user()->hasRole('Senior Staff')){
        } else {
            return back()->with('error', 'You have insufficient permissions to view this ticket. If this is a mistake, please contact Joshua M');
        }

        $ticket = FeedbackSubmission::find($id);
        $ticket_fields = FeedbackTypeFieldSubmission::where('submission_id', $id)->get();
        
        return view('admin.community.tickets.view', compact('ticket', 'ticket_fields'));
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

    // Admin Pickup Ticket
    public function adminPickupTicket($ticket_id)
    {
        if(auth()->user()->hasRole('Senior Staff')){
        } else {
            return back()->with('error', 'You have insufficient permissions to pick up this ticket. If this is a mistake, please contact Joshua M');
        }

        $ticket = FeedbackSubmission::find($ticket_id);

        $ticket->assigned_user = auth()->user()->id;
        $ticket->status = 1;
        $ticket->save();

        return back()->with('success', 'You have successfully picked up this Ticket!');
    }

    // Admin Drop Ticket
    public function adminDropTicket($ticket_id)
    {
        if(auth()->user()->hasRole('Senior Staff')){
        } else {
            return back()->with('error', 'You have insufficient permissions to drop this ticket. If this is a mistake, please contact Joshua M');
        }

        $ticket = FeedbackSubmission::find($ticket_id);

        $ticket->assigned_user = null;
        $ticket->status = 0;
        $ticket->save();

        return back()->with('success', 'You have successfully dropped this Ticket!');
    }
}
