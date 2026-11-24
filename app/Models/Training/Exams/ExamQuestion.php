<?php

namespace App\Models\Training\Exams;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = ['exam_id', 'question', 'required', 'active'];

    protected $casts = ['required' => 'boolean', 'active' => 'boolean'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(ExamQuestionOption::class);
    }

    /** Like VATCAN: a question with no answer set is never asked. */
    public function isAskable(): bool
    {
        return $this->options->count() >= 2 && $this->options->where('is_correct', true)->count() === 1;
    }
}
