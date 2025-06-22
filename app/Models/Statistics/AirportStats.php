<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Statistics\FlightAirpots;

class AirportStats extends Model
{
    use HasFactory;

    protected $table = "statistics_airports";
    protected $fillable = ['id', 'airport', 'current_dep', 'current_arr', 'last_month_dep', 'last_month_arr', 'year_dep', 'year_arr'];
       
    public function info()
    {
        return $this->hasOne(FlightAirports::class, 'icao', 'airport');
    }
}
