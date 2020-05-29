<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class AuditLogEntry extends Model
{
    protected $fillable = [
        'user_id', 'action', 'affected_id', 'time', 'private',
    ];

    public static function insert(User $user, $message, User $affected_user, $private)
    {
        $log = new self;
        $log->action = $message;
        $log->user_id = $user->id;
        $log->affected_id = $affected_user->id;
        $log->time = date('Y-m-d H:i:s');
        $log->private = $private;
        $log->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_id');
    }
}
