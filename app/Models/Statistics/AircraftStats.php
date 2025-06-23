<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Statistics\FlightAircraft;

class AircraftStats extends Model
{
    use HasFactory;

    protected $table = "statistics_aircraft";
    protected $fillable = ['id', 'code', 'current', 'last_month', 'year'];
       
    public function info()
    {
        return $this->hasOne(FlightAircraft::class, 'code', 'code');
    }
}
