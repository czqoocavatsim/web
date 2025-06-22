<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Statistics\FlightAirlines;

class AirlineStats extends Model
{
    use HasFactory;

    protected $table = "statistics_airline";
    protected $fillable = ['id', 'airline', 'current', 'last_month', 'year'];
       
    public function info()
    {
        return $this->hasOne(FlightAirlines::class, 'id', 'airline');
    }
}
