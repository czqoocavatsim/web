<?php

namespace App\Models\ControllerBookings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

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
