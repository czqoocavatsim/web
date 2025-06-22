<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class ControllerStats extends Model
{
    use HasFactory;

    protected $table = "statistics_controller";
    protected $fillable = ['id', 'cid', 'current', 'last_month', 'year'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cid');
    }
        
}
