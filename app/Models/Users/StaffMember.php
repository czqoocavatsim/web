<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Users\StaffMember
 *
 * @property int $id
 * @property string $position
 * @property string $group
 * @property string $description
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shortform
 * @property int $user_id
 * @property int|null $group_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereShortform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffMember whereUserId($value)
 * @mixin \Eloquent
 */
class StaffMember extends Model
{
    protected $table = 'staff_member';
    use LogsActivity;

    /**
     * The attributes mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'position', 'group', 'description', 'email', 'shortform', 'group_id'
    ];

    /**
     * Returns the user associated with the staff member.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns whether the position is vacant.
     *
     * @return void
     */
    public function vacant()
    {
        return $this->user_id === 1;
    }

    /**
     * Assign a user (person) to the position.
     *
     * @param User $user
     * @return void
     */
    public function assignUser(User $assignedUser)
    {
        //Assign
        $this->user = $assignedUser;
        $this->save();

        //Log
        activity()->causedBy(auth()->user())->performedOn($this)->log('Changed position holder to '.$assignedUser->id);
    }

    /**
     * Vacate the role.
     *
     * @return void
     */
    public function vacate()
    {

    }
}
