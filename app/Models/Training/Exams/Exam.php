<?php

namespace App\Models\Training\Exams;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name', 'slug', 'active', 'pass_percentage', 'questions_to_ask',
        'initial_retake_days', 'subsequent_retake_days', 'time_limit_minutes', 'notify_cids',
    ];

    protected $casts = ['active' => 'boolean'];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /** Questions that can actually be asked: active, with exactly one correct option and at least two options. */
    public function askableQuestions()
    {
        return $this->questions()->where('active', true)->with('options')->get()->filter->isAskable()->values();
    }
}
