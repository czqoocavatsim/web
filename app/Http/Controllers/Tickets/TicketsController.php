<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Settings\CoreSettings;
use App\Mail\NewTicketMail;
use App\Mail\NewTicketReplyMail;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketReply;
use App\Models\Users\User;
use App\Models\Users\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function index()
    {
        $openTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 0)->get()->sortByDesc('id');
        $closedTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 1)->get()->sortByDesc('id');
        $onHoldTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 2)->get()->sortByDesc('id');

        return view('dashboard.tickets.index', compact('openTickets', 'closedTickets', 'onHoldTickets'));
    }

    public function staffIndex()
    {
        $openTickets = Ticket::where('status', 0)->get()->sortByDesc('id');
        $closedTickets = Ticket::where('status', 1)->get()->sortByDesc('id');
        $onHoldTickets = Ticket::where('status', 2)->get()->sortByDesc('id');

        return view('dashboard.tickets.staff', compact('openTickets', 'closedTickets', 'onHoldTickets'));
    }

    public function viewTicket($id)
    {
        if (Auth::user()->permissions < 2) {
            $ticket = Ticket::where('ticket_id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        } else {
            $ticket = Ticket::where('ticket_id', $id)->firstOrFail();
        }

        $replies = TicketReply::where('ticket_id', $id)->get()->sortBy('submission_time');

        return view('dashboard.tickets.viewticket', compact('ticket', 'replies'));
    }

    public function startNewTicket(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:50',
            'message' => 'required|min:25',
            'department' => 'required',
        ]);

        $ticket = new Ticket([
            'user_id' => Auth::user()->id,
            'ticket_id' => Str::random(6),
            'department' => $request->get('department'),
            'title' => $request->get('title'),
            'message' => $request->get('message'),
            'status' => 0,
            'submission_time' => date('Y-m-d H:i:s'),
        ]);

        $ticket->save();

        if ($ticket->department == 'firchief') {
            Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailfirchief)->cc(CoreSettings::whereId(1)->firstOrFail()->emailwebmaster)->send(new NewTicketMail($ticket));
        } elseif ($ticket->department == 'chiefinstructor') {
            Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailcinstructor)->cc(CoreSettings::whereId(1)->firstOrFail()->emailwebmaster)->send(new NewTicketMail($ticket));
        } elseif ($ticket->department == 'webmaster') {
            Mail::to(CoreSettings::whereId(1)->firstOrFail()->emailwebmaster)->send(new NewTicketMail($ticket));
        } else {
            Mail::to(CoreSettings::where('id', 1)->firstOrFail()->emailfirchief)->cc(CoreSettings::whereId(1)->firstOrFail()->emailcinstructor)->cc(CoreSettings::whereId(1)->firstOrFail()->emailwebmaster)->send(new NewTicketMail($ticket));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket '.$ticket->ticket_id.' created! A staff member will respond soon.');
    }

    public function closeTicket($id)
    {
        if (Auth::user()->permissions < 2) {
            $ticket = Ticket::where('ticket_id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        } else {
            $ticket = Ticket::where('ticket_id', $id)->firstOrFail();
        }

        if ($ticket->status != 1) {
            $ticketReply = new TicketReply([
                'user_id' => 1,
                'ticket_id' => $ticket->ticket_id,
                'message' => 'Ticket closed by '.Auth::user()->fname.' '.Auth::user()->lname.' '.Auth::user()->id.' at '.date('Y-m-d H:i:s').'. If you require further assistance please open a new ticket.',
                'submission_time' => date('Y-m-d H:i:s'),
            ]);
            $ticketReply->save();
            $ticket->status = 1;
            $ticket->updated_at = date('Y-m-d H:i:s');
            $ticket->save();
            $notification = new UserNotification([
                'user_id' => $ticket->user_id,
                'content' => 'Your ticket '.$ticket->ticket_id.' was closed.',
                'link' => route('tickets.viewticket', $ticket->ticket_id),
                'dateTime' => date('Y-m-d H:i:s'),
            ]);
            $notification->save();

            return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('success', 'Ticket closed!');
        } else {
            return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('error', 'Ticket is already closed.');
        }
    }

    public function addReplyToTicket(Request $request, $ticket_id)
    {
        if (Auth::user()->permissions < 2) {
            $ticket = Ticket::where('ticket_id', $ticket_id)->where('user_id', Auth::user()->id)->firstOrFail();
        } else {
            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        }

        $validatedData = $request->validate([
            'message' => 'required',
        ]);

        $ticketReply = new TicketReply([
            'user_id' => Auth::user()->id,
            'ticket_id' => $ticket->ticket_id,
            'message' => $request->get('message'),
            'submission_time' => date('Y-m-d H:i:s'),
        ]);

        $ticketReply->save();
        $ticket->updated_at = date('Y-m-d H:i:s');
        $ticket->save();

        if ($ticketReply->user_id != $ticket->user_id) {
            $notification = new UserNotification([
                'user_id' => $ticket->user_id,
                'content' => 'Your ticket '.$ticket->ticket_id.' has a new reply from '.User::find($ticketReply->user_id)->fname.User::find($ticketReply->user_id)->lname.'!',
                'link' => route('tickets.viewticket', $ticket->ticket_id),
                'dateTime' => date('Y-m-d H:i:s'),
            ]);
            $notification->save();
        }

        Mail::to($ticket->user->email)->send(new NewTicketReplyMail($ticketReply, $ticket));

        return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('success', 'Reply sent.');
    }
}
