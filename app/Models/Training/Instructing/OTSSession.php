<?php

namespace App\Models\Training\Instructing;

use Illuminate\Database\Eloquent\Model;

class OTSSession extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id', 'scheduled_time', 'remarks', 'results', 'position_id'
    ];

    protected $dates = [
        'scheduled_time'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function position()
    {
        return $this->hasOne(MonitoredPosition::class, 'position_id');
    }
}
