<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Times of the year to remove members
class MemberRemovalTime extends Model
{
    protected $fillable = [
        'id', 'day', 'month'
      ];
}
