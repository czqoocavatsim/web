<?php

namespace App\Models\Users;

use App\Models\News\Announcement;
use App\Models\Training\ControllerAcknowledgement;
use App\Models\News\News;
use Illuminate\Support\Carbon;
use App\Models\Users\StaffMember;
use Spatie\Activitylog\LogOptions;
use App\Models\Roster\RosterMember;
use App\Models\Training\Application;
use App\Models\Users\UserPreferences;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Users\UserPrivacyPreferences;
use App\Models\Users\UserNotificationPreferences;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use App\Models\Training\Instructing\Students\Student;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Training\Instructing\Instructors\Instructor;

/**
 * App\Models\Users\User
 *
 * @property int $id
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property int|null $rating_id
 * @property string|null $rating_short
 * @property string|null $rating_long
 * @property string|null $rating_GRP
 * @property string|null $reg_date
 * @property string|null $region_code
 * @property string|null $region_name
 * @property string|null $division_code
 * @property string|null $division_name
 * @property string|null $subdivision_code
 * @property string|null $subdivision_name
 * @property int $gdpr_subscribed_emails
 * @property int $deleted
 * @property int $init
 * @property string $avatar
 * @property string|null $bio
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $display_cid_only
 * @property string|null $display_fname
 * @property int $display_last_name
 * @property int|null $discord_user_id
 * @property int|null $discord_dm_channel_id
 * @property int $avatar_mode
 * @property int $used_connect
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Application[] $applications
 * @property-read int|null $applications_count
 * @property-read Instructor|null $instructorProfile
 * @property-read \Illuminate\Database\Eloquent\Collection|News[] $news
 * @property-read int|null $news_count
 * @property-read \App\Models\Users\UserNotificationPreferences|null $notificationPreferences
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Users\UserPreferences|null $preferences
 * @property-read \App\Models\Users\UserPrivacyPreferences|null $privacyPreferences
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read RosterMember|null $rosterProfile
 * @property-read \App\Models\Users\StaffMember|null $staffProfile
 * @property-read Student|null $studentProfile
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDiscordDmChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDiscordUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayCidOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDivisionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDivisionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGdprSubscribedEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingGRP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSubdivisionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSubdivisionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsedConnect($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use LogsActivity;

    protected static $logName = 'confidential';
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fname', 'lname', 'email', 'rating_id', 'rating_short', 'rating_long', 'rating_GRP',
        'reg_date', 'region_code', 'region_name', 'division_code', 'division_name',
        'subdivision_code', 'subdivision_name', 'permissions', 'init', 'gdpr_subscribed_emails', 'avatar', 
        'bio', 'display_cid_only', 'display_fname', 'display_last_name', 'discord_user_id', 'member_of_czqo',
        'discord_username', 'discord_avatar', 'discord_dm_channel_id', 'avatar_mode', 'used_connect', 'vatsim_gdpr_account',
        'pilotrating_id', 'pilotrating_short', 'pilotrating_long', 'militaryrating_id', 'militaryrating_short', 'militaryrating_long',
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
     * Is the user a bot?
     *
     * @return bool
     */
    public function isBot()
    {
        if ($this->id == 1 || $this->id == 2) {
            return true;
        }

        return false;
    }

    // // Assign role to user Model
    // public function assignRole($role)
    // {
    //     return $role;
    // }

    // // Remove role from user Model
    // public function removeRole($role)
    // {

    // }

    /**
     * Return articles that the user has written.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }

    /**
     * Return the users applications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Return their instructor profile, if they have one.
     *
     * @return App\Models\Training\Instructing\Instructors\Instructor
     */
    public function instructorProfile()
    {
        return $this->hasOne(Instructor::class);
    }

    /**
     * Return their student profile, if they have one.
     *
     * @return App\Models\Training\Instructing\Students\Student
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Return their staff member profile, if they're a staff member.
     *
     * @return App\Models\Users\StaffProfile
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffMember::class);
    }

    /**
     * Return their roster member profile, if they're on the roster.
     *
     * @return App\Models\Roster\RosterMember
     */
    public function rosterProfile()
    {
        return $this->hasOne(RosterMember::class);
    }

    /**
     * Days user has existed.
     *
     * @return int
     */
    public function userSinceInDays()
    {
        $created = $this->created_at;
        $now = Carbon::now();
        $difference = $created->diff($now)->days;

        return $difference;
    }

    /**
     * The user's highest assigned role.
     *
     * @return Spatie\Permission\Models\Role
     */
    public function highestRole()
    {
        //If the user doesnt have a role, then give them one temporarily.
        if (count($this->roles) == 0) {
            //Assign them guest
            $this->assignRole('Guest');
        }

        return $this->roles[0];
    }



    /**
     * Get the user's name in requested format.
     * FLC - First, Last, CID
     * FL - First, Last
     * F - First.
     *
     * @param string $format
     *
     * @return string|null
     */
    public function fullName($format)
    {
        if ($format == 'FLC') {
            if($this->display_last_name == 0) {
                return $this->fname.' - '.$this->id;
            } elseif ($this->display_last_name == 1) {
                return $this->fname.' '.$this->lname.' - '.$this->id;
            } elseif($this->display_last_name == 2){
                return $this->fname.' '.substr($this->lname, 0, 1).' - '.$this->id;
            }
        } elseif ($format === 'FL') {
            if($this->display_last_name == 0) {
                return $this->fname;
            } elseif ($this->display_last_name == 1) {
                return $this->fname.' '.$this->lname;
            } elseif($this->display_last_name == 2){
                return $this->fname.' '.substr($this->lname, 0, 1);
            }
        } elseif ($format === 'F') {
            return $this->fname;
        }

        return null;
    }


    /**
     * Is the user's avatar the default (initials) avatar?
     *
     * @return bool
     */
    public function isAvatarDefault()
    {
        if ($this->avatar_mode == 0) {
            return true;
        }

        return false;
    }

    /**
     * Returns their Discord DM channel snowflake ID for notifications.
     *
     * @return int|null
     */
    public function routeNotificationForDiscord()
    {
        return $this->discord_dm_channel_id;
    }

    /**
     * Does the user have a linked Discord account?
     *
     * @return bool
     */
    public function hasDiscord()
    {
        if (!$this->discord_user_id) {
            return false;
        }

        return true;
    }

    /**
     * Returns the user's Discord avatar URL.
     *
     * @return string|null
     */
    public function getDiscordAvatar()
    {
        return $this->discord_avatar;
    }

    public function genInitialAvatar()
    {
        $avatar = new InitialAvatar();
        $image = $avatar->name($this->fullName('FL'))
            ->size(125)
            ->background('#cfeaff')
            ->color('#2196f3')
            ->generate();

        Storage::put('files/avatars/'.$this->id.'/initials.png', (string) $image->encode('png'));
        return;
    }

    /**
     * Returns the user's avatar.
     *
     * @param bool $external If URL should be an external URL.
     *
     * @return string URL to avatar image.
     */
    public function avatar($external = false)
    {
        if ($this->avatar_mode == 0) {
            return '/assets/files/avatars/'.$this->id.'/initials.png';
        } elseif ($this->avatar_mode == 1) {
            if ($external) {
                return URL('/').$this->avatar;
            } else {
                return $this->avatar;
            }
        } else {
            return $this->getDiscordAvatar();
        }
    }

    /**
     * Returns the user's preferences (general).
     *
     * @return App\Models\Users\UserPreferences
     */
    public function preferences()
    {
        return $this->hasOne(UserPreferences::class);
    }

    /**
     * Returns the user's notification preferences.
     *
     * @return App\Models\Users\UserNotificationPreferences
     */
    public function notificationPreferences()
    {
        return $this->hasOne(UserNotificationPreferences::class);
    }

    /**
     * Returns the user's privacy preferences.
     *
     * @return App\Models\Users\UserPrivacyPreferences
     */
    public function privacyPreferences()
    {
        return $this->hasOne(UserPrivacyPreferences::class);
    }

    /**
     * Return's a pending application from the user if there is one.
     *
     * @return App\Models\Training\Application|null
     */
    public function pendingApplication()
    {
        if ($pendingApp = Application::where('user_id', $this->id)->where('status', 0)->first()) {
            return $pendingApp;
        }

        return null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }

    public function getUnreadAcknowledgements()
    {
        $controllerAcknowledgements = Announcement::where('controller_acknowledgement', true)->get();
        return $controllerAcknowledgements->filter(function ($acknowledgement) {
            return !ControllerAcknowledgement::where('user_id', $this->id)->where('announcement_id', $acknowledgement->id)->exists();
        });
    }
}
