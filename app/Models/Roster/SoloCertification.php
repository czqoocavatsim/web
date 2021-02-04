<?php

namespace App\Models\Roster;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Roster\SoloCertification
 *
 * @property int $id
 * @property int $roster_member_id
 * @property \Illuminate\Support\Carbon|null $expires
 * @property int $instructor_id
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $expiry_notification_sent
 * @property \Illuminate\Support\Carbon|null $expiry_notification_time
 * @property-read User $instructor
 * @property-read \App\Models\Roster\RosterMember $rosterMember
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification query()
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereExpiryNotificationSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereExpiryNotificationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereRosterMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoloCertification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SoloCertification extends Model
{
    public function rosterMember()
    {
        return $this->belongsTo(RosterMember::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = [
        'expires', 'expiry_notification_time',
    ];
}
