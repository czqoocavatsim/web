<?php

namespace App\Models\Training\Instructing\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

class StudentAvailabilitySubmission extends Model
{
    protected $hidden = ['id'];

    protected $protected = [
        'student_id', 'submission', 'extra_comments'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function submissionHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->submission));
    }

    public function extraCommentsHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->extra_comments));
    }
}
