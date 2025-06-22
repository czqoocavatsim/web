<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightAirports extends Model
{
    use HasFactory;

    protected $table = "flight_airport";
    protected $fillable = ['id', 'icao', 'iata', 'name'];
        
}
