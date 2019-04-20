<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $table = "policies";

    protected $fillable = [
        'name', 'details', 'link', 'embed', 'author', 'releaseDate', 'staff_only'
    ];
}
