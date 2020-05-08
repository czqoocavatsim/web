<?php

namespace App\Models\Feedback;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class ControllerFeedback extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'controller_cid', 'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
