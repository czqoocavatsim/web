<?php

namespace App\Models\Roster;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class SoloCertification extends Model
{
    public function rosterMember()
    {
        return $this->belongsTo(RosterMember::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = [
        'expires', 'expiry_notification_time',
    ];
}
