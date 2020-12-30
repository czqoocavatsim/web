<?php

namespace App\Models\Training\Instructing\Records;

use App\Models\Network\MonitoredPosition;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

class OTSSession extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'student_id', 'assessor_id', 'scheduled_time', 'remarks', 'result', 'position_id'
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
        return $this->belongsTo(Instructor::class, 'assessor_id');
    }

    public function position()
    {
        return $this->belongsTo(MonitoredPosition::class, 'position_id');
    }

    public function remarksHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->remarks));
    }
}
