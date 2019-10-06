<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructingSession extends Model
{
    protected $fillable = [
        'student_id', 'instructor_id', 'type', 'start_time', 'end_time', 'network_callsign', 'instructor_comments', 'status',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(\App\Instructor::class);
    }
}
