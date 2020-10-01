<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

class ControllerApplication extends Model
{
    use LogsActivity;

    protected $table = "event_controller_applications";

    protected $fillable = [
        'id', 'event_id', 'user_id', 'start_availability_timestamp', 'end_availability_timestamp', 'comments', 'submission_timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
