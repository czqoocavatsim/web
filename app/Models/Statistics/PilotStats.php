<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class PilotStats extends Model
{
    use HasFactory;

    protected $table = "statistics_pilot";
    protected $fillable = ['id', 'cid', 'hours'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cid', 'current', 'last_month', 'year');
    }
        
}
