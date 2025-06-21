<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class ControllerStats extends Model
{
    use HasFactory;

    protected $table = "statistics_controller_last";
    protected $fillable = ['id', 'cid', 'hours'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cid');
    }
        
}
