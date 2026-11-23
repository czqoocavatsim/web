<?php

namespace App\Models\Training\Exams;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id', 'user_id', 'status', 'started_at', 'submitted_at',
        'correct_count', 'total_questions', 'score_percent', 'pass_percentage',
    ];

    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAttemptAnswer::class)->orderBy('position');
    }

    public function isFinished(): bool
    {
        return $this->status !== 'in_progress';
    }
}
