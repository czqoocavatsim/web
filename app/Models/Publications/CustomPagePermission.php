<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CustomPagePermission extends Model
{
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
