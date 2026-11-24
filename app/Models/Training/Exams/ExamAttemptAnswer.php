<?php

namespace App\Models\Training\Exams;

use Illuminate\Database\Eloquent\Model;

class ExamAttemptAnswer extends Model
{
    protected $fillable = ['exam_attempt_id', 'exam_question_id', 'position', 'exam_question_option_id', 'is_correct'];

    protected $casts = ['is_correct' => 'boolean'];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    public function option()
    {
        return $this->belongsTo(ExamQuestionOption::class, 'exam_question_option_id');
    }
}
