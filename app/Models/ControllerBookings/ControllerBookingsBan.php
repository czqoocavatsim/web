<?php

namespace App\Models\ControllerBookings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class ControllerBookingsBan extends Model
{
    protected $fillable = [
        'user_id', 'reason', 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
