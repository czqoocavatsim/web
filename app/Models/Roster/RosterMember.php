<?php

namespace App\Models\Roster;

use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RosterMember extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cid', 'user_id', 'certification', 'date_certified', 'active', 'monthly_hours', 'remarks'
    ];

    protected $hidden = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLeaderboardHours() { // Get hours from leaderboard
        return $this->monthly_hours;
    }

    public function meetsActivityRequirement()
    {
        // Get date certified
        try {
            $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->date_certified);
            error_log($certifiedDate);
        } catch (\InvalidArgumentException $e) { // Catch exception if date is null
            $certifiedDate = null;
        }

        if($this->active) {

            // Check if certified in last 6mo
            $diff = $certifiedDate != null ? Carbon::now()->diffInMonths($certifiedDate) : null; // Get date diff

            // If less than 6 months
            if ($diff != null && $diff <= 6) {
                switch($diff) { // Switch the activity and check appropriate hours based on number
                    case 0:
                        break;
                    case 1: // 1 month
                        return $this->currency >= 1.0 ?: false;
                        break;
                    case 2: // 2 months
                        return $this->currency >= 2.0 ?: false;
                        break;
                    case 3: // 3 months
                        return $this->currency >= 3.0 ?: false;
                        break;
                    case 4: // 4 months
                        return $this->currency >= 4.0 ?: false;
                        break;
                    case 5: // 5 months
                        return $this->currency >= 5.0 ?: false;
                        break;
                    default: // Default
                        $isActive = true;
                        break;
                }
            }
            else {
                //Does not meet requirement
                return false;
            }
        }

        return false;
    }
}
