<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class MaintenanceIPExemption extends Model
{
    protected $fillable = [
        'id', 'label', 'ipv4'
    ];
}
