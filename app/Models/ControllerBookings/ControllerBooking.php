<?php

namespace App\Models\ControllerBookings;

use App\Models\Network\VatsimPosition;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ControllerBookings\ControllerBooking
 *
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBooking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBooking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBooking query()
 * @mixin \Eloquent
 */
class ControllerBooking extends Model
{
    protected $fillable = [
        'user_id', 'position_id', 'start_time', 'end_time', 'notes',
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
