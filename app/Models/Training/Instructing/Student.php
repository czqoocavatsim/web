<?php

namespace App\Models\Training\Instructing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'current'
    ];

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
}
