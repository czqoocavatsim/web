<?php

namespace App\Models\AtcTraining;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class RosterMember extends Model
{
    protected $table = 'roster';

    protected $fillable = [
        'cid', 'user_id', 'full_name', 'rating', 'division', 'status', 'active', 'currency', 'monthly_hours', 'date_certified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLeaderboardHours() { // Get hours from leaderboard
        return $this->monthly_hours;
    }
}
