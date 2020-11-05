<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class SupportTicketTarget extends Model
{
    protected $table = 'support_tickets_targets';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
