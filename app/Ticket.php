<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'ticket_id', 'department', 'title', 'message', 'status', 'submission_time'
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
