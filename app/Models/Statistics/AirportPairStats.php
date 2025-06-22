<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Statistics\FlightAirpots;

class AirportPairStats extends Model
{
    use HasFactory;

    protected $table = "statistics_airport_pairs";
    protected $fillable = ['id', 'airport1', 'airport2', 'current', 'last_month', 'year'];
       
    public function info1()
    {
        return $this->hasOne(FlightAirports::class, 'icao', 'airport1');
    }

    public function info2()
    {
        return $this->hasOne(FlightAirports::class, 'icao', 'airport2');
    }
}
