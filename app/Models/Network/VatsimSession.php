<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;

class VatsimSession extends Model
{
    protected $fillable = [
        'controller', 'vatsim_cid', 'position', 'session_start', 'session_end', 'session',
    ];
}
