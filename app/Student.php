<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'status', 'instructor_id', 'last_status_change', 'accepted_application'
    ];
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function instructor(){
        return $this->belongsTo('App\Instructor');
    }

    public function getApplicationAttribute(){
        return Application::whereId($this->accepted_application)->firstOrFail();
    }

    public function instructingSessions(){
        return $this->hasMany('App\InstructingSession');
    }
}

