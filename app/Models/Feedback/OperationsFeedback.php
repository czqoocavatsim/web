<?php

namespace App\Models\Feedback;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class OperationsFeedback extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'subject', 'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
