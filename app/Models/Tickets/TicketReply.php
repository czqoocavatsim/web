<?php

namespace App\Models\Tickets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use App\Models\Users\User;

class TicketReply extends Model
{
    protected $fillable = [
        'user_id', 'ticket_id', 'message', 'submission_time',
    ];

    protected $table = 'ticket_reply';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function html()
    {
        return new HtmlString(app(\Parsedown::class)->text($this->message));
    }

    public function submission_time_pretty()
    {
        return Carbon::create($this->submission_time)->toDayDateTimeString().' Zulu';
    }
}
