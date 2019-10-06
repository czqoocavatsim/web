<?php

namespace App;

use App\Http\Controllers\RosterController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fname', 'lname', 'email', 'rating_id', 'rating_short', 'rating_long', 'rating_GRP',
        'reg_date', 'region_code', 'region_name', 'division_code', 'division_name',
        'subdivision_code', 'subdivision_name', 'permissions', 'init', 'gdpr_subscribed_emails', 'avatar', 'bio', 'display_cid_only', 'display_fname', 'display_last_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Return articles that the user has written.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany('App\News');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function instructorProfile()
    {
        return $this->hasOne(Instructor::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffMember::class);
    }

    public function rosterProfile()
    {
        return $this->hasOne(RosterMember::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    public function userSinceInDays()
    {
        $created = $this->created_at;
        $now = Carbon::now();
        $difference = $created->diff($now)->days;

        return $difference;
    }

    public function fullName($format)
    {
        //display name check
        if ($this->display_cid_only == true) {
            return strval($this->id);
        }

        if ($format == 'FLC') {
            if ($this->display_last_name == true) {
                return $this->display_fname.' '.$this->lname.' '.$this->id;
            } else {
                return $this->display_fname.' '.$this->id;
            }
        } elseif ($format === 'FL') {
            if ($this->display_last_name == true) {
                return $this->display_fname.' '.$this->lname;
            } else {
                return $this->display_fname;
            }
        } elseif ($format === 'F') {
            return $this->display_fname;
        }

        abort(500);
    }

    public function isAvatarDefault()
    {
        if ($this->avatar === 'https://www.drupal.org/files/profile_default.png') {
            return true;
        }

        return false;
    }

    public function certified()
    {
        if ($this->rosterProfile()) {
            return true;
        }

        return false;
    }

    public function bookingBanned()
    {
        if (ControllerBookingsBan::where('user_id', $this->id)->first()) {
            return true;
        }

        return false;
    }

    /*public function hasRole($role)
    {
        return User::where('role', $role)->get();
    }*/
}
