<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShanwickController extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'controller_cid', 'name', 'rating', 'division'];
        
}
