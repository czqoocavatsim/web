<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightAirlines extends Model
{
    use HasFactory;

    protected $table = "flight_airlines";
    protected $fillable = ['id', 'icao', 'iata', 'name'];
        
}
