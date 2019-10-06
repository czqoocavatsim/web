<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffMember extends Model
{
    protected $table = 'staff_member';

    protected $fillable = [
        'user_id', 'position', 'group', 'description', 'email',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
