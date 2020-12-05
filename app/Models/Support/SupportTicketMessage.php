<?php

namespace App\Models\Support;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class SupportTicketMessage extends Model
{
    protected $fillable = [
        'user_id', 'ticket_id', 'message', 'system_msg'
    ];

    protected $id = ['id', 'ticket_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function messageHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->message));
    }
}
