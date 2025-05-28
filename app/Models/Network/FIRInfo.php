<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FIRInfo extends Model
{
    use HasFactory;

    protected $table = "fir_info";
    protected $fillable = ['id', 'eggx', 'czqo', 'ganwick', 'partnership_firs', 'lppo', 'bird', 'kzny', 'all', 'updated_at'];
        
}
