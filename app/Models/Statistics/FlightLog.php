<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightLog extends Model
{
    use HasFactory;

    protected $table = "flights_log";
    protected $fillable = ['id', 'cid', 'callsign', 'airline', 'dep', 'arr', 'aircraft', 'fl', 'direction', 'still_inside', 'save_details'];
        
}
