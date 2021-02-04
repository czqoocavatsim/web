<?php

namespace App\Models\ControllerBookings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ControllerBookings\ControllerBookingsBan
 *
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBookingsBan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBookingsBan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControllerBookingsBan query()
 * @mixin \Eloquent
 */
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
