<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'id', 'fname', 'lname', 'email', 'rating', 'division', 'permissions', 'init', 'gdpr_subscribed_emails', 'avatar'
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
     * Return articles that the user has written
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

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    public function userSinceInDays(){

        $created = $this->created_at;
        $now = Carbon::now();
        $difference = $created->diff($now)->days;

        return $difference;
    }

    public function fullName($format)
    {
        if ($format == "FLC") {
            return $this->fname.' '.$this->lname.' '.$this->id;
        } elseif ($format === "FL") {
            return $this->fname.' '.$this->lname;
        }

        abort(500);
    }

    /*public function hasRole($role)
    {
        return User::where('role', $role)->get();
    }*/
}
