<?php

namespace App\Models\Training\Instructing\Students;

use App\Models\Roster\SoloCertification;
use App\Models\Training\Application;
use App\Models\Training\Instructing\Links\InstructorStudentAssignment;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Records\InstuctorRecommendation;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\StudentNote;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'current'
    ];

    public function instructor()
    {
        //Find instructor this user is assigned to
        return InstructorStudentAssignment::where('student_id', $this->id)->first();
    }

    public function labels()
    {
        return $this->hasMany(StudentStatusLabelLink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(StudentNote::class, 'student_id');
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class, 'student_id');
    }

    public function otsSessions()
    {
        return $this->hasMany(OTSSession::class, 'student_id');
    }

    public function application()
    {
        //Find latest accepted application from user
        return Application::where('user_id', $this->user_id)->where('status', 1)->latest('created_at')->first();
    }

    public function availability()
    {
        return $this->hasMany(StudentAvailabilitySubmission::class);
    }

    public function recommendations()
    {
        return $this->hasMany(InstuctorRecommendation::class);
    }

    public function assignStatusLabel(StudentStatusLabel $label)
    {
        //Create link
        $link = new StudentStatusLabelLink([
            'student_id' => $this->id,
            'student_status_label_id' => $label->id
        ]);
        $link->save();
    }

    public function soloCertification()
    {
        //Find solo certification for student
        try {
            return SoloCertification::where('roster_member_id', $this->user->rosterProfile->id)->where('expires', '>', Carbon::now())->first();
        } catch(Exception $e) { return null; }
    }

    public function setAsReadyForAssessment()
    {
        if ($this->hasLabel("Ready for Assessment")) {
            return true;
        }

        return false;
    }

    public function hasLabel($label_text)
    {
        if (!StudentStatusLabel::whereName($label_text)->first()) return false;
        if ($label = StudentStatusLabelLink
            ::where('student_id', $this->id)
            ->where('student_status_label_id',
                StudentStatusLabel::whereName($label_text)->first()->id
            )->first()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Return's the student's next scheduled training session if there is one.
     *
     * @return App\Models\Training\Instructing\Records\TrainingSession|null
     */
    public function upcomingTrainingSession()
    {
        if ($session = TrainingSession::where('student_id', $this->id)->where('scheduled_time', '>', Carbon::now())->first()) { return $session; }
        return null;
    }

    /**
     * Return's the student's next scheduled OTS session if there is one.
     *
     * @return App\Models\Training\Instructing\Records\OTSSession|null
     */
    public function upcomingOtsSession()
    {
        if ($session = OTSSession::where('student_id', $this->id)->where('scheduled_time', '>', Carbon::now())->first()) { return $session; }
        return null;
    }
}
