<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use App\Models\Training;
use App\Models\Community\Discord\DiscordBan;
use App\Models\ControllerBookings;
use App\Models\Events;
use App\Models\Network;
use App\Models\News\News;
use App\Models\Publications;
use App\Models\Roster\RosterMember;
use App\Models\Settings;
use App\Models\Tickets;
use App\Models\Training\Application;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\Student;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use RestCord\DiscordClient;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Throwable;

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
        'subdivision_code', 'subdivision_name', 'permissions', 'init', 'gdpr_subscribed_emails', 'avatar', 'bio', 'display_cid_only', 'display_fname', 'display_last_name',
        'discord_user_id', 'discord_dm_channel_id', 'avatar_mode', 'used_connect'
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
     * @return boolean
     */
    public function isBot()
    {
        if ($this->id == 1 || $this->id == 2) {
            return true;
        }

        return false;
    }

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
     * @return integer
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

            //Should probably inform
            Log::alert('User '.$this->id.' did not have any role assigned. Guest role assigned.');
        }

        return $this->roles[0];
    }

    /**
     * Get the user's name in requested format.
     * FLC - First, Last, CID
     * FL - First, Last
     * F - First
     *
     * @param string $format
     * @return string|null
     */
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

        return null;
    }

    /**
     * Is the user's avatar the default (initials) avatar?
     *
     * @return boolean
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
     * @return integer|null
     */
    public function routeNotificationForDiscord()
    {
        return $this->discord_dm_channel_id;
    }

    /**
     * Does the user have a linked Discord account?
     *
     * @return boolean
     */
    public function hasDiscord()
    {
        if (!$this->discord_user_id) { return false; }
        return true;
    }

    /**
     * Returns the user's Discord account data.
     *
     * @return \RestCord\Model\User\User|null
     */
    public function getDiscordUser()
    {
        return Cache::remember('users.discorduserdata.'.$this->id, 84600, function () {
            $discord = new DiscordClient(['token' => config('services.discord.token')]);
            $user = $discord->user->getUser(['user.id' => $this->discord_user_id]);
            return $user;
        });
    }

    /**
     * Returns the user's Discord avatar URL.
     *
     * @return string|null
     */
    public function getDiscordAvatar()
    {
        return Cache::remember('users.discorduserdata.'.$this->id.'.avatar', 21600, function () {
            $discord = new DiscordClient(['token' => config('services.discord.token')]);
            $user = $discord->user->getUser(['user.id' => $this->discord_user_id]);
            $url = 'https://cdn.discordapp.com/avatars/'.$user->id.'/'.$user->avatar.'.png';
            return $url;
        });
    }

    /**
     * Returns a true/false of whether the user is a member of the Discord guild.
     *
     * @return boolean
     */
    public function memberOfCzqoGuild()
    {
        $discord = new DiscordClient(['token' => config('services.discord.token')]);
        try {
            if ($discord->guild->getGuildMember(['guild.id' => 479250337048297483, 'user.id' => $this->discord_user_id])) {
                return true;
            }
        }
        catch (Throwable $ex) {
            return false;
        }
        return false;
    }

    /**
     * Returns the user's avatar.
     *
     * @param boolean $external If URL should be an external URL.
     * @return string URL to avatar image.
     */
    public function avatar($external = false)
    {
        if ($this->avatar_mode == 0) {
            $avatar = Cache::remember('users.'.$this->id.'.initialsavatar', 172800, function () {
                $avatar = new InitialAvatar();
                $image = $avatar
                    ->name($this->fullName('FL'))
                    ->size(125)
                    ->background('#cfeaff')
                    ->color('#2196f3')
                    ->generate();
                Storage::put('public/files/avatars/'.$this->id.'/initials.png', (string) $image->encode('png'));
                return Storage::url('public/files/avatars/'.$this->id.'/initials.png');
                imagedestroy($image);
            });
            if ($external) {
                return URL('/').$avatar;
            } else {
                return $avatar;
            }
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
        if ($pendingApp = Application::where('user_id', $this->id)->where('status', 0)->first()) { return $pendingApp; }
        return null;
    }
}
