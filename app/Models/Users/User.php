<?php

namespace App\Models\Users;

use App\Http\Controllers\RosterController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use App\Models\Training;
use App\Models\Community\Discord\DiscordBan;
use App\Models\ControllerBookings;
use App\Models\Events;
use App\Models\Network;
use App\Models\News;
use App\Models\Publications;
use App\Models\Settings;
use App\Models\Tickets;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        return $this->hasMany(News\News::class);
    }

    public function applications()
    {
        return $this->hasMany(Training\Application::class);
    }

    public function instructorProfile()
    {
        return $this->hasOne(AtcTraining\Instructor::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(AtcTraining\Student::class);
    }

    public function tickets()
    {
        return $this->hasMany(Tickets\Ticket::class);
    }

    public function ticketReplies()
    {
        return $this->hasMany(Tickets\TicketReply::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffMember::class);
    }

    public function rosterProfile()
    {
        return $this->hasOne(AtcTraining\RosterMember::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    public function bookingBanObj()
    {
        return $this->hasOne(ControllerBookings\ControllerBookingsBan::class);
    }

    public function userSinceInDays(){
        $created = $this->created_at;
        $now = Carbon::now();
        $difference = $created->diff($now)->days;

        return $difference;
    }

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

    public function isAvatarDefault()
    {
        if ($this->avatar_mode == 0) {
            return true;
        }

        return false;
    }

    public function certified()
    {
        if (!$this->rosterProfile()) {
            return false;
        }

        return true;
    }

    public function bookingBanned()
    {
        if (!ControllerBookings\ControllerBookingsBan::where('user_id', $this->id)->first()) {
            return false;
        }

        return true;
    }

    public function routeNotificationForDiscord()
    {
        return $this->discord_dm_channel_id;
    }

    public function hasDiscord()
    {
        if (!$this->discord_user_id) { return false; }
        return true;
    }

    public function getDiscordUser()
    {
        return Cache::remember('users.discorduserdata.'.$this->id, 84600, function () {
            $discord = new DiscordClient(['token' => config('services.discord.token')]);
            $user = $discord->user->getUser(['user.id' => $this->discord_user_id]);
            return $user;
        });
    }

    public function getDiscordAvatar()
    {
        return Cache::remember('users.discorduserdata.'.$this->id.'.avatar', 21600, function () {
            $discord = new DiscordClient(['token' => config('services.discord.token')]);
            $user = $discord->user->getUser(['user.id' => $this->discord_user_id]);
            $url = 'https://cdn.discordapp.com/avatars/'.$user->id.'/'.$user->avatar.'.png';
            return $url;
        });
    }

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

    public function currentDiscordBan()
    {
        $ban = DiscordBan::whereDate('ban_end_timestamp','>',Carbon::now())->where('user_id', $this->id)->first();
        if ($ban) {
            return $ban;
        } else {
            return null;
        }
    }

    public function discordBans()
    {
        return $this->hasMany(DiscordBan::class);
    }

    public function avatar()
    {
        if ($this->avatar_mode == 0) {
            return Cache::remember('users.'.$this->id.'.initialsavatar', 172800, function () {
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
        } elseif ($this->avatar_mode == 1) {
            return $this->avatar;
        } else {
            return $this->getDiscordAvatar();
        }
    }

    public function preferences()
    {
        return $this->hasOne(UserPreferences::class);
    }
}
