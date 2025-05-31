<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FIRAircraft extends Model
{
    use HasFactory;

    protected $table = "fir_current_aircraft";
    protected $fillable = ['id', 'cid', 'callsign', 'still_inside', 'point_recorded', 'exited_oca'];
        
}
