<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    protected $fillable = [
        'application_id', 'user_id', 'status', 'submitted_at', 'processed_at', 'processed_by', 'applicant_statement', 'staff_comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
