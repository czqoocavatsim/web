<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Statistics\FlightAircraft;

class LevelStats extends Model
{
    use HasFactory;

    protected $table = "statistics_levels";
    protected $fillable = ['id', 'aircraft', 'current', 'last_month', 'year'];
}