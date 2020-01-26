<?php

namespace App\Models\AtcTraining;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class RosterMember extends Model
{
    protected $table = 'roster';

    protected $fillable = [
        'cid', 'user_id', 'full_name', 'rating', 'division', 'status', 'active', 'currency'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
