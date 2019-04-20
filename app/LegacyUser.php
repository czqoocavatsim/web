<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegacyUser extends Model
{
    protected $table = "legacyroster";
    protected $fillable = [
        'id', 'name', 'rating', 'subdivision', 'certification', 'status'
    ];
}
