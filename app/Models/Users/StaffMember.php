<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use Spatie\Activitylog\Traits\LogsActivity;

class StaffMember extends Model
{
    protected $table = 'staff_member';
    use LogsActivity;

    protected $fillable = [
        'user_id', 'position', 'group', 'description', 'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vacant()
    {
        if ($this->user_id == 1) return true;
        return false;
    }
}
