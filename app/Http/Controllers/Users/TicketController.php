<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedbackSubmission;
use App\Models\Feedback\FeedbackComment;
use App\Models\Feedback\FeedbackTypeFieldSubmission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    public function adminViewTicket($slug)
    {
        // Get Ticket Details
        $ticket = FeedbackSubmission::where('slug', $slug)->first();
        
        if($ticket){
            $ticket_fields = FeedbackTypeFieldSubmission::where('id', $ticket->id)->get();
            $ticket_comments = FeedbackComment::where('feedback_submission_id', $ticket->id)->orderBy('created_at', 'desc')->get();
        } else {
            return back()->with('error', 'Ticket with Slug ID '.$slug.' does not exist');
        }

        return view('admin.community.tickets.view', compact('ticket', 'ticket_fields', 'ticket_comments'));
    }

    // Admin Send Comment
    public function adminAddTicketComment(Request $request, $slug)
    {
        //Define validator messages
        $messages = [
            'comment.required' => 'Please write your comment.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return back()->with('error', 'Comment Content was not found. No comment was added to this ticket.');
        }

        $ticket_comment = new FeedbackComment([
            'feedback_submission_id'    => $request->submission_id,
            'comment_type'              => $request->comment_type,
            'user_id'                   => Auth::user()->id,
            'submission_content'        => $request->comment,
        ]);
        $ticket_comment->save();

        $ticket = FeedbackSubmission::find($request->submission_id);
        $ticket->status = $request->ticket_status;
        $ticket->save();

        return back()->with('Success', 'Comment has successfully been added!');

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
    public function adminPickupTicket($slug)
    {
        $ticket = FeedbackSubmission::where('slug', $slug)->first();

        $ticket->assigned_user = auth()->user()->id;
        $ticket->status = 1;
        $ticket->save();

        return back()->with('success', 'You have successfully picked up this Ticket!');
    }

    // Admin Drop Ticket
    public function adminDropTicket($slug)
    {
        $ticket = FeedbackSubmission::where('slug', $slug)->first();

        $ticket->assigned_user = null;
        $ticket->status = 0;
        $ticket->save();

        return back()->with('success', 'You have successfully dropped this Ticket!');
    }
}
