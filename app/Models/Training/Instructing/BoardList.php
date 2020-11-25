<?php

namespace App\Models\Training\Instructing;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    protected $table = "board_lists";

    protected $fillable = [
        'name', 'description', 'visible', 'order'
    ];

    
}
