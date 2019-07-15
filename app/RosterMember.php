<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RosterMember extends Model
{
    protected $table = "roster";

    protected $fillable = [
        'cid', 'user_id', 'full_name', 'rating', 'division', 'status', 'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
