<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\StaffGroup
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $can_receive_tickets
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\StaffMember[] $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereCanReceiveTickets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffGroup extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'can_receive_tickets',
    ];

    public function members()
    {
        return $this->hasMany(StaffMember::class, 'group_id');
    }
}
