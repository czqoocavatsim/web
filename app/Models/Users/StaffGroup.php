<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class StaffGroup extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'can_receive_tickets'
    ];

    public function members()
    {
        return $this->hasMany(StaffMember::class, 'group_id');
    }
}
