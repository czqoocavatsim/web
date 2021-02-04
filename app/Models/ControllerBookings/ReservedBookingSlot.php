<?php

namespace App\Models\ControllerBookings;

use App\Models\Network\VatsimPosition;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ControllerBookings\ReservedBookingSlot
 *
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ReservedBookingSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReservedBookingSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReservedBookingSlot query()
 * @mixin \Eloquent
 */
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
