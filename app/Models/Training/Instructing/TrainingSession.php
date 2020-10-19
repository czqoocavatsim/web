<?php

namespace App\Models\Training\Instructing;

use App\Models\Network\MonitoredPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'instructor_id', 'scheduled_time', 'remarks', 'position_id'
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
