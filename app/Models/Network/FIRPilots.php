<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class FIRPilots extends Model
{
    use HasFactory;

    protected $table = "fir_pilot_stats";
    protected $fillable = ['id', 'month_stats', 'year_stats', 'updated_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }
        
}
