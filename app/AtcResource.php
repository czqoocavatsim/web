<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtcResource extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'url',
    ];
}
