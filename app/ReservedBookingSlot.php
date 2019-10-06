<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservedBookingSlot extends Model
{
    protected $fillable = [
        'user_id', 'position_id', 'start_time', 'end_time', 'notes', 'permission_exemption',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(VatsimPosition::class, 'position_id');
    }
}
