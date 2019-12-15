<?php

namespace App\Models\AtcTraining;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'status', 'instructor_id', 'last_status_change', 'accepted_application',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function getApplicationAttribute()
    {
        return Application::whereId($this->accepted_application)->firstOrFail();
    }

    public function instructingSessions()
    {
        return $this->hasMany(InstructingSession::class);
    }
}
