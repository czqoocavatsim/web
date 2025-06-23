<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightAircraft extends Model
{
    use HasFactory;

    protected $table = "flight_aircraft";
    protected $fillable = ['id', 'code', 'name'];
        
}