<?php

namespace App\Models\Roster;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Support\HtmlString;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roster\SoloCertification;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Roster\RosterMember
 *
 * @property int $id
 * @property int $cid
 * @property int $user_id
 * @property string $certification
 * @property string|null $date_certified
 * @property int $active
 * @property float|null $monthly_hours
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $currency
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereCertification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereDateCertified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereMonthlyHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RosterMember whereUserId($value)
 * @mixin \Eloquent
 */
class RosterMember extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cid', 'user_id', 'certification', 'date_certified', 'active', 'monthly_hours', 'remarks',
    ];

    protected $hidden = ['id', 'user_id', 'date_certified', 'monthly_hours', 'remarks', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLeaderboardHours()
    { // Get hours from leaderboard
        return $this->monthly_hours;
    }

    public function activeSoloCertification()
    {
        $cert = SoloCertification::where('expires', '>', Carbon::now())->where('roster_member_id', $this->id)->first();
        if ($cert) {
            return $cert;
        }

        return null;
    }

    public function certificationPretty()
    {
        switch ($this->certification) {
            case 'certified':
                return 'Certified';
            break;
            case 'not_certified':
                return 'Not Certified';
            break;
            case 'training':
                return 'Student';
            break;
            default:
                'Unknown';
        }
    }

    public function activePretty()
    {
        if ($this->active) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }

    public function meetsActivityRequirement()
    {
        //Returns false only if we want them to get an inactivity email 
        $date = false;
        // Get date certified
        try {
            $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->date_certified);
        } catch (\InvalidArgumentException $e) { // Catch exception if date is null
            $date = true;
        }

        //If they are already inactive they can't control to meet activity in first place.
        if (!$this->active){
            return true;
        }

        //Don't wanna send an inactivity email to someone who isn't certified
        if ($this->certification != 'certified'){
            return true;
        }

        //If within quarter nah skip since policy.
        if ($date === false && ($certifiedDate > Carbon::now()->startOfQuarter() && $certifiedDate < Carbon::now()->endOfQuarter())){
            return true;
        }
        
        //If they are meet activity requirements why send em an email?
        if ($this->currency >= 6.0) {
            return true;
        }
        
        //Finally send em an email!
        return false;
    }

    public function certificationLabelHtml()
    {
        $html = "<span style='font-weight: 400' class='badge rounded p-2 shadow-none ";

        //Colour
        switch ($this->certification) {
            case 'certified':
                $html .= "green text-white'><i class='fas fa-check-double mr-2'></i>";
            break;
            case 'not_certified':
                $html .= "red text-white'><i class='fas fa-times mr-2'></i>";
            break;
            case 'training':
                $html .= "orange text-white'><i class='fas fa-graduation-cap mr-2'></i>";
            break;
            default:
                $html .= "grey text-white'><i class='fas fa-question mr-2'></i>";
        }

        $html .= $this->certificationPretty().'</span>';

        return new HtmlString($html);
    }

    public function activeLabelHtml()
    {
        $html = "<span style='font-weight: 400' class='badge rounded p-2 shadow-none ";

        //Colour
        if ($this->active) {
            $html .= "green text-white'><i class='fas fa-check mr-2'></i>";
        } else {
            $html .= "red text-white'><i class='fas fa-times mr-2'></i>";
        }

        $html .= $this->activePretty().'</span>';

        return new HtmlString($html);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
