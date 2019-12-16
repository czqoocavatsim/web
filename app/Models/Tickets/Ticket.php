<?php

namespace App\Models\Tickets;

use App\Models\Users\StaffMember;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use App\Models\Users\User;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'ticket_id', 'staff_member_id', 'title', 'message', 'status', 'submission_time',
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff_member()
    {
        return $this->belongsTo(StaffMember::class);
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->message));
    }

    public function updated_at_pretty()
    {
        return Carbon::create($this->updated_at->toDateTimeString())->diffForHumans();
    }

    public function submission_time_pretty()
    {
        return Carbon::create($this->submission_time)->toDayDateTimeString().' Zulu';
    }
}
