<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Settings\CoreSettings;
use App\Mail\NewTicketMail;
use App\Mail\NewTicketReplyMail;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketReply;
use App\Models\Users\StaffGroup;
use App\Models\Users\StaffMember;
use App\Models\Users\User;
use App\Models\Users\UserNotification;
use App\Notifications\NewTicket;
use App\Notifications\TicketReply as NotificationsTicketReply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function index()
    {
        $openTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 0)->get()->sortByDesc('id');
        $closedTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 1)->get()->sortByDesc('id');
        $onHoldTickets = Ticket::where('user_id', Auth::user()->id)->where('status', 2)->get()->sortByDesc('id');
        $staff_members = StaffMember::where('user_id', '!=', 1)->where('group_id', 1)->get();
        $groups = StaffGroup::where('can_receive_tickets', true)->get();

        return view('dashboard.tickets.index', compact('openTickets', 'closedTickets', 'onHoldTickets', 'staff_members', 'groups'));
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
        $messages = [
            'title.required' => 'A ticket title is required.',
            'title.max' => 'A ticket title may not be over 50 characters in length.',
            'message.required' => 'A message is required.',
            'message.min' => 'The message must be at least 25 characters long. You can see the length of your message below the Markdown editor.',
            'staff_member.required' => 'You need to specify the recipient of the ticket.'
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'message' => 'required|min:25',
            'staff_member' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error-modal', $validator->errors()->all());
        }

        $ticket = new Ticket([
            'user_id' => Auth::user()->id,
            'ticket_id' => Str::random(6),
            'staff_member_id' => StaffMember::where('shortform', $request->get('staff_member'))->first()->id,
            'title' => $request->get('title'),
            'message' => $request->get('message'),
            'status' => 0,
            'submission_time' => date('Y-m-d H:i:s'),
        ]);

        $ticket->save();

        $ticket->staff_member->user->notify(new NewTicket($ticket->staff_member, $ticket));

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
                'message' => 'Ticket closed by '.Auth::user()->fullName('FLC').' at '.Carbon::now()->toDayDateTimeString().' Zulu. If you require further assistance please open a new ticket.',
                'submission_time' => date('Y-m-d H:i:s'),
            ]);
            $ticketReply->save();
            $ticket->status = 1;
            $ticket->updated_at = date('Y-m-d H:i:s');
            $ticket->save();

            return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('success', 'Ticket closed!');
        } else {
            return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('error-modal', 'Ticket '.$ticket->id.' is already closed.');
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

        if ($ticketReply->user == $ticket->user) {
            $ticket->staff_member->user->notify(new NotificationsTicketReply($ticket->staff_member->user, $ticket, $ticketReply));
        } else {
            $ticket->user->notify(new NotificationsTicketReply($ticket->user, $ticket, $ticketReply));
        }

        return redirect()->route('tickets.viewticket', $ticket->ticket_id)->with('success', 'Reply sent.');
    }
}
