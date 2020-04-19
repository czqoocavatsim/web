<?php

namespace App\Models\News;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class HomeNewControllerCert extends Model
{
    public function controller()
    {
        return $this->belongsTo(User::class, 'controller_id');
    }
}
